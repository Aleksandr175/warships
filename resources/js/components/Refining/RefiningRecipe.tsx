import React from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import styled from "styled-components";
import { Icon } from "../Common/Icon";
import {
  ICity,
  ICityResource,
  IRefiningQueue,
  IRefiningRecipe,
} from "../../types/types";
import { getResourceSlug } from "../../utils";
import { Controller, useForm } from "react-hook-form";
import { InputNumber } from "../Common/InputNumber";
import { useMutateRefiningQueue } from "../../hooks/useMutateRefiningQueue";

interface IFormValues {
  qty: number;
}

export const RefiningRecipe = ({
  recipe,
  city,
  cityResources,
  setQueue,
  updateCityResources,
  hasAvailableSlots,
}: {
  recipe: IRefiningRecipe;
  cityResources: ICityResource[];
  city: ICity;
  setQueue: (queue: IRefiningQueue[]) => void;
  updateCityResources: (resources: ICityResource[]) => void;
  hasAvailableSlots: boolean;
}): React.ReactElement => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const form = useForm<IFormValues>({
    defaultValues: {
      qty: 0,
    },
  });

  const maxAvailableRecipes = () => {
    const cityResourceAmount =
      cityResources.find(
        (resource) => resource.resourceId === recipe.inputResourceId
      )?.qty || 0;

    return Math.min(Math.floor(cityResourceAmount / recipe.inputQty), 100);
  };

  const { handleSubmit, formState, control, reset } = form;

  const { mutate: mutateRefiningQueue, isPending } = useMutateRefiningQueue({
    onSuccess: (response: any) => {
      reset({
        qty: 0,
      });
      setQueue(response.data.queue);
      updateCityResources(response.data.cityResources);
    },
  });

  const { isValid } = formState;
  const onSubmit = (data: IFormValues) => {
    console.log(data);
    mutateRefiningQueue({
      cityId: city.id,
      qty: data.qty,
      recipeId: recipe.refiningRecipeId,
    });
  };

  if (!dictionaries) {
    return <></>;
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
            min: 1,
          }}
          render={({ field }) => {
            return (
              <InputNumberStyled
                {...field}
                onChange={(value) => {
                  field.onChange(value);
                }}
                maxNumber={maxAvailableRecipes()}
                disabled={!hasAvailableSlots}
              />
            );
          }}
        />

        <button
          className={"btn btn-primary"}
          disabled={!isValid || !hasAvailableSlots}
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
