import React, { useEffect, useState } from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { SContent, SH1, SH2 } from "../styles";
import styled, { css } from "styled-components";
import { Icon } from "../Common/Icon";
import { ProgressBar } from "../Common/ProgressBar";
import { httpClient } from "../../httpClient/httpClient";
import { ICity, IRefiningQueue, IRefiningRecipeForm } from "../../types/types";
import { useFetchRefiningRecipes } from "../../hooks/useFetchRefiningRecipes";
import { getResourceSlug, getTimeLeft } from "../../utils";
import { Controller, useFieldArray, useForm } from "react-hook-form";
import { InputNumber } from "../Common/InputNumber";

interface IFormValues {
  refiningRecipes: IRefiningRecipeForm[];
}

export const Refining = ({ city }: { city: ICity }) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const numberOfSlots = 4; // TODO: get it from server

  const [refiningQueue, setRefiningQueue] = useState<IRefiningQueue[]>([]);

  const getRefiningQueue = () => {
    if (!city?.id) {
      return;
    }

    httpClient.get("/refining?cityId=" + city?.id).then((response) => {
      setRefiningQueue(response.data.refiningQueue);
    });
  };

  useEffect(() => {
    getRefiningQueue();
  }, []);

  const queryRefiningRecipes = useFetchRefiningRecipes();

  const form = useForm<IFormValues>({
    defaultValues: {
      refiningRecipes: [],
    },
  });

  const { handleSubmit, control, reset } = form;

  const { fields: refiningRecipes } = useFieldArray({
    control, // control props comes from useForm (optional: if you are using FormContext)
    name: "refiningRecipes",
  });

  useEffect(() => {
    if (queryRefiningRecipes.data) {
      reset({
        refiningRecipes: queryRefiningRecipes.data.refiningRecipes,
      });
    }
  }, [queryRefiningRecipes?.data]);

  const renderSlots = () => {
    const slots = [];

    for (let i = 0; i < numberOfSlots; i++) {
      if (refiningQueue[i]) {
        const refining = refiningQueue[i];
        let timeLeft = getTimeLeft(refining.deadline);

        if (timeLeft < 0) {
          timeLeft = 0;
        }

        slots.push(
          <SRefiningSlot empty={false} key={refining.deadline}>
            <SRefiningMaterials>
              {dictionaries?.resourcesDictionary && (
                <SRefiningMaterialSlot>
                  <Icon
                    title={getResourceSlug(
                      dictionaries?.resourcesDictionary,
                      refining.inputResourceId
                    )}
                    size={"big"}
                  />
                </SRefiningMaterialSlot>
              )}
              <SRefiningMaterialTitle>
                {refining.outputQty}
              </SRefiningMaterialTitle>
              {dictionaries?.resourcesDictionary && (
                <SRefiningMaterialSlot>
                  <Icon
                    title={getResourceSlug(
                      dictionaries?.resourcesDictionary,
                      refining.outputResourceId
                    )}
                    size={"big"}
                  />
                </SRefiningMaterialSlot>
              )}
            </SRefiningMaterials>

            <SProgressWrapper>
              <ProgressBar
                percent={Math.ceil(
                  ((refining.time - timeLeft) / refining.time) * 100
                )}
              />
            </SProgressWrapper>
          </SRefiningSlot>
        );
      } else {
        slots.push(
          <SRefiningSlot empty={true} key={i}>
            <SRefiningMaterials>
              <SRefiningMaterialTitle>Free Slot</SRefiningMaterialTitle>
            </SRefiningMaterials>
          </SRefiningSlot>
        );
      }
    }

    return slots;
  };

  // TODO: send only one recipe to backend
  const onSubmit = (data: IFormValues) => {
    console.log(data);
  };

  if (!dictionaries) {
    return null;
  }

  return (
    <SContent>
      <SH1>Refining Resources</SH1>
      <SRefiningSlots>{renderSlots()}</SRefiningSlots>

      <SH2>Refining Recipes</SH2>

      {refiningRecipes?.map((recipe, index) => {
        return (
          <SRecipe key={recipe.id}>
            <SRecipeResource>
              <Icon
                title={getResourceSlug(
                  dictionaries?.resourcesDictionary,
                  recipe.inputResourceId
                )}
                size={"big"}
              />
              <SXIcon>x</SXIcon>
              {recipe.inputQty}
            </SRecipeResource>

            <Icon title={"arrow"} size={"big"} />

            <SRecipeResource>
              <Icon
                title={getResourceSlug(
                  dictionaries?.resourcesDictionary,
                  recipe.outputResourceId
                )}
                size={"big"}
              />
              <SXIcon>x</SXIcon>
              {recipe.outputQty}
            </SRecipeResource>

            <div>
              <Controller
                name={`refiningRecipes.${index}.qty`}
                control={control}
                render={({ field }) => {
                  return (
                    <InputNumberStyled
                      {...field}
                      onChange={(value) => {
                        field.onChange(value);
                      }}
                      /*disabled={
                        !hasAllRequirements("warship", selectedWarshipId) ||
                        !availableWarships
                      }*/
                    />
                  );
                }}
              />

              {/* TODO: send only one recipe to backend */}
              <button
                className={"btn btn-primary"}
                /*disabled={!isValid}*/
                onClick={handleSubmit(onSubmit)}
              >
                Order
              </button>
            </div>
          </SRecipe>
        );
      })}
    </SContent>
  );
};

const SRefiningSlots = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 40px;
`;

const SRefiningSlot = styled.div<{ empty?: boolean }>`
  position: relative;
  width: 160px;
  height: 160px;
  border-radius: var(--block-border-radius);
  background: rgb(0, 0, 0);
  background: linear-gradient(
    0deg,
    rgba(160, 205, 245, 1) 0%,
    rgba(42, 97, 244, 1) 100%
  );

  &:after {
    display: block;
    position: absolute;
    content: "";
    width: 100px;
    height: 100px;
    top: 50%;
    left: 50%;
    margin-top: -40px;
    margin-left: -50px;

    ${({ empty }) =>
      empty
        ? css`
            background: url("../../../images/icons/pot-empty.svg") no-repeat;
            background-size: contain;
          `
        : css`
            background: url("../../../images/icons/pot.svg") no-repeat;
            background-size: contain;
          `};
  }
`;

const SRefiningMaterials = styled.div`
  display: flex;
  margin-top: 10px;
  padding: 0 25px;
  justify-content: space-between;
  align-items: center;
`;

const SRefiningMaterialSlot = styled.div`
  width: 32px;
  height: 32px;
  border-radius: var(--block-border-radius);
  background: #e7ecfd;
`;

const SRefiningMaterialTitle = styled.div`
  color: white;
  font-size: 20px;
  width: 100%;
  text-align: center;
`;

const SProgressWrapper = styled.div`
  position: absolute;
  bottom: 10px;
  left: 10px;
  right: 10px;
`;

const SRecipe = styled.div`
  display: flex;
  gap: 10px;
  align-items: center;
  margin-bottom: 20px;
`;

const SRecipeResource = styled.div`
  display: flex;
  align-items: center;
  gap: 10px;

  color: #616267;
`;

const SXIcon = styled.span`
  display: inline-block;
  margin-left: 5px;
`;

const InputNumberStyled = styled(InputNumber)`
  display: inline-block;
  width: 80px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
`;
