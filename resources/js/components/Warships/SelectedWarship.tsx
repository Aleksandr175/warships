import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime, getResourceSlug } from "../../utils";
import React, { useEffect } from "react";
import styled from "styled-components";
import { httpClient } from "../../httpClient/httpClient";
import {
  ICityBuilding,
  ICityResource,
  ICityWarship,
  ICityWarshipQueue,
  IUserResearch,
  IWarship,
  IWarshipRequiredResource,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";
import { InputNumber } from "../Common/InputNumber";
import { Controller, useForm } from "react-hook-form";
import { FieldErrors } from "react-hook-form/dist/types/errors";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";

interface IProps {
  selectedWarshipId: number;
  cityId: number;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  setWarships: (warships: ICityWarship[]) => void;
  getWarships: () => void;
  queue?: ICityWarshipQueue[];
  setQueue: (q: ICityWarshipQueue[]) => void;
  getQty: (warshipId: number) => number;
  researches: IUserResearch[];
  buildings: ICityBuilding[];
  hasAvailableSlots: boolean;
}

interface IFormValues {
  selectedQty: string | number | null;
  cityResources: ICityResource[];
}

const DEFAULT_VALUES = {
  selectedQty: null,
};

type IGroupedCityResources = Record<number, ICityResource[]>;

export const SelectedWarship = ({
  selectedWarshipId,
  cityId,
  buildings,
  updateCityResources,
  cityResources,
  setQueue,
  researches,
  getQty,
  setWarships,
  hasAvailableSlots,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const selectedWarship = getWarship(selectedWarshipId)!;
  const requiredResources = selectedWarship.requiredResources;
  const time = selectedWarship?.time || 0;
  const attack = selectedWarship?.attack || 0;
  const speed = selectedWarship?.speed || 0;
  const health = selectedWarship?.health || 0;
  const capacity = selectedWarship?.capacity || 0;

  const form = useForm({
    // TODO: refactor it
    defaultValues: {
      ...DEFAULT_VALUES,
      cityResources,
    },
    resolver: (data) => {
      const errors: FieldErrors = {};

      // TODO: do i need it?
      if (isWarshipDisabled()) {
        // @ts-ignore
        errors.warshipIsDisabled = "Warship is disabled";
      }

      if (!data.selectedQty || data.selectedQty < 0) {
        // @ts-ignore
        errors.selectedQty = "required";
      }

      if (!hasAllRequirements("warship", selectedWarshipId)) {
        // @ts-ignore
        errors.hasAllRequirements = "Don't have requirements";
      }

      return {
        values: data,
        errors,
      };
    },
  });

  const { formState, handleSubmit, getValues, control, reset } = form;

  useEffect(() => {
    // TODO: refactor it
    reset({
      selectedQty: getValues("selectedQty"),
      cityResources,
    });
  }, [cityResources]);

  const { isValid } = formState;

  function getWarship(warshipId: number): IWarship | undefined {
    return dictionaries?.warshipsDictionary.find((w) => w.id === warshipId);
  }

  const calculateAvailableWarships = (
    cityResources: ICityResource[],
    requiredResources: IWarshipRequiredResource[]
  ): number => {
    // Step 1: Group city resources by resourceId
    const groupedCityResources: IGroupedCityResources = cityResources.reduce(
      (acc, resource) => {
        acc[resource.resourceId] = acc[resource.resourceId] || [];
        acc[resource.resourceId].push(resource);
        return acc;
      },
      {} as IGroupedCityResources
    );

    // Step 2-4: Calculate available warships
    return requiredResources.reduce((minAvailable, requiredResource) => {
      const availableQty =
        groupedCityResources[requiredResource.resourceId]?.reduce(
          (total, resource) => total + resource.qty,
          0
        ) || 0;

      const maxAvailable = Math.floor(availableQty / requiredResource.qty);
      return maxAvailable < minAvailable ? maxAvailable : minAvailable;
    }, Infinity);
  };

  const availableWarships = calculateAvailableWarships(
    cityResources,
    requiredResources
  );

  const isWarshipDisabled = () => {
    return !availableWarships;
  };

  function run(warshipId: number, qty: number) {
    httpClient
      .post("/warships/create", {
        cityId,
        warshipId,
        qty,
      })
      .then((response) => {
        setWarships(response.data.warships);
        setQueue(response.data.queue);
        updateCityResources(response.data.cityResources);
      });
  }

  // TODO: add dependencies
  const {
    hasRequirements,
    hasAllRequirements,
    getRequirements,
    getRequiredItem,
  } = useRequirementsLogic({
    buildings,
    researches,
  });

  const onSubmit = (data: IFormValues) => {
    run(selectedWarshipId, Number(data.selectedQty ? data.selectedQty : 0));
    reset(DEFAULT_VALUES);
  };

  if (!dictionaries) {
    return null;
  }

  return (
    <SSelectedItem {...form} className={"row"}>
      <div className={"col-4"}>
        <SCardWrapper>
          <Card
            objectId={selectedWarship.id}
            labelText={getQty(selectedWarshipId)}
            timer={0}
            imagePath={"warships"}
          />
        </SCardWrapper>
      </div>
      <div className={"col-8"}>
        <SH2>{selectedWarship?.title}</SH2>
        <div>
          <SText>Required resources:</SText>
          <SParams>
            {requiredResources?.map((resource) => {
              return (
                <SParam key={resource.resourceId}>
                  <Icon
                    title={getResourceSlug(
                      dictionaries.resourcesDictionary,
                      resource.resourceId
                    )}
                  />
                  {resource.qty}
                </SParam>
              );
            })}
            <SParam>
              <Icon title={"time"} /> {convertSecondsToTime(time)}
            </SParam>
          </SParams>
        </div>
        <div>
          <SText>Warship Params:</SText>
          <SParams>
            <SParam>
              <Icon title={"capacity"} /> {capacity}
            </SParam>
            <SParam>
              <Icon title={"attack"} /> {attack}
            </SParam>
            <SParam>
              <Icon title={"health"} /> {health}
            </SParam>
            <SParam>
              <Icon title={"speed"} /> {speed}
            </SParam>
          </SParams>
        </div>
        <div>
          {/* TODO: add max available warships we can build with one slot */}
          <SText>You can build: {availableWarships}</SText>
        </div>
        <SButtonsBlock>
          <Controller
            name="selectedQty"
            control={control}
            render={({ field }) => {
              return (
                <InputNumberStyled
                  {...field}
                  onChange={(value) => {
                    if (!value) {
                      value = 0;
                    }

                    if (value > 0) {
                      if (value > availableWarships) {
                        value = availableWarships;
                      }

                      field.onChange(value);
                    } else {
                      field.onChange(null);
                    }
                  }}
                  disabled={
                    !hasAllRequirements("warship", selectedWarshipId) ||
                    !availableWarships ||
                    !hasAvailableSlots
                  }
                />
              );
            }}
          />

          <button
            className={"btn btn-primary"}
            disabled={!isValid || !hasAvailableSlots}
            onClick={handleSubmit(onSubmit)}
          >
            Create
          </button>
        </SButtonsBlock>

        <SText>{selectedWarship?.description}</SText>

        {hasRequirements("warship", selectedWarshipId) && (
          <>
            <SText>It requires:</SText>
            {getRequirements("warship", selectedWarshipId)?.map(
              (requirement) => {
                const requiredItem = getRequiredItem(requirement);

                return (
                  <SText key={requiredItem?.title}>
                    {requiredItem?.title}, {requirement.requiredEntityLvl} lvl
                  </SText>
                );
              }
            )}
          </>
        )}
      </div>
    </SSelectedItem>
  );
};

const SSelectedItem = styled.div`
  margin-bottom: calc(var(--block-gutter-y) * 2);
  min-height: 300px;
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;

const InputNumberStyled = styled(InputNumber)`
  display: inline-block;
  width: 80px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
`;
