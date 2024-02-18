import React from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import styled from "styled-components";
import { Icon } from "../Common/Icon";
import { ICityResource, IRefiningRecipe } from "../../types/types";
import { getResourceSlug } from "../../utils";
import { Controller, useForm } from "react-hook-form";
import { InputNumber } from "../Common/InputNumber";

interface IFormValues {
  qty: number | null;
}

export const RefiningRecipe = ({
  recipe,
  cityResources,
}: {
  recipe: IRefiningRecipe;
  cityResources: ICityResource[];
}) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const form = useForm<IFormValues>({
    defaultValues: {
      qty: null,
    },
  });

  const maxAvailableRecipes = () => {
    const cityResourceAmount =
      cityResources.find(
        (resource) => resource.resourceId === recipe.inputResourceId
      )?.qty || 0;

    return Math.min(Math.floor(cityResourceAmount / recipe.inputQty), 100);
  };

  console.log(maxAvailableRecipes());

  const { handleSubmit, formState, control, reset } = form;

  const { isValid } = formState;
  const onSubmit = (data: IFormValues) => {
    console.log(data);
  };

  if (!dictionaries) {
    return null;
  }

  return (
    <SRecipe>
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
          name={`qty`}
          control={control}
          rules={{
            required: true,
          }}
          render={({ field }) => {
            return (
              <InputNumberStyled
                {...field}
                onChange={(value) => {
                  field.onChange(value);
                }}
                maxNumber={maxAvailableRecipes()}
              />
            );
          }}
        />

        <button
          className={"btn btn-primary"}
          disabled={!isValid}
          onClick={handleSubmit(onSubmit)}
        >
          Order
        </button>
      </div>
    </SRecipe>
  );
};

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
