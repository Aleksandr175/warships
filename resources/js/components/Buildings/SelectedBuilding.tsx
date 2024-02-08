import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime, getResourceSlug } from "../../utils";
import React from "react";
import styled from "styled-components";
import { httpClient } from "../../httpClient/httpClient";
import {
  IBuilding,
  IBuildingResource,
  ICity,
  ICityBuilding,
  ICityBuildingQueue,
  ICityResource,
  IUserResearch,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";

interface IProps {
  selectedBuildingId: number;
  city: ICity;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  buildings: ICityBuilding[] | undefined;
  setBuildings: (buildings: ICityBuilding[]) => void;
  queue?: ICityBuildingQueue;
  setQueue: (q: ICityBuildingQueue | undefined) => void;
  researches: IUserResearch[];
  timeLeft: number;
  getLvl: (buildingId: number) => number;
}

export const SelectedBuilding = ({
  selectedBuildingId,
  buildings,
  setBuildings,
  city,
  updateCityResources,
  cityResources,
  queue,
  setQueue,
  researches,
  timeLeft,
  getLvl,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const getResourcesForBuilding = (
    buildingId: number,
    lvl: number
  ): IBuildingResource[] => {
    return (
      dictionaries?.buildingResources.filter(
        (br) => br.buildingId === buildingId && br.lvl === lvl
      ) || []
    );
  };

  const getBuilding = (buildingId: number): IBuilding | undefined => {
    return dictionaries?.buildings.find(
      (building) => building.id === buildingId
    );
  };

  const run = (buildingId: number) => {
    httpClient
      .post("/build", {
        cityId: city.id,
        buildingId,
      })
      .then((response) => {
        setBuildings(response.data.buildings);
        setQueue(response.data.buildingQueue);
        updateCityResources(response.data.cityResources);
      });
  };

  const cancel = (buildingId: number) => {
    httpClient
      .post("/build/" + buildingId + "/cancel", {
        cityId: city.id,
      })
      .then((response) => {
        setBuildings(response.data.buildings);
        setQueue(undefined);

        updateCityResources(response.data.cityResources);
      });
  };

  const isCurrentBuildingInProcess =
    queue && queue.buildingId === selectedBuildingId;
  const isSomeBuildingInProcess = queue && queue.buildingId > 0;

  const {
    hasRequirements,
    hasAllRequirements,
    getRequirements,
    getRequiredItem,
  } = useRequirementsLogic({
    buildings,
    researches,
  });

  const selectedBuilding = getBuilding(selectedBuildingId)!;
  const lvl = getLvl(selectedBuildingId);
  const nextLvl = lvl + 1;

  const requiredResources = getResourcesForBuilding(
    selectedBuildingId,
    nextLvl
  );
  const timeRequired = requiredResources[0]?.timeRequired || 0;

  const isBuildingDisabled = (): boolean => {
    for (const resource of requiredResources) {
      const cityResource = cityResources.find(
        (cr) => cr.resourceId === resource.resourceId
      );

      if (!cityResource || cityResource.qty < resource.qty) {
        return true;
      }
    }

    if (!requiredResources?.length) {
      return true;
    }

    return false;
  };

  // production of next lvl building
  const production = dictionaries?.buildingsProduction.filter((bProduction) => {
    return (
      bProduction.buildingId === selectedBuildingId &&
      bProduction.lvl === nextLvl
    );
  });

  if (!dictionaries) {
    return null;
  }

  return (
    <SSelectedItem className={"row"}>
      <div className={"col-4"}>
        <SCardWrapper>
          <Card
            object={selectedBuilding}
            qty={lvl}
            timer={queue?.buildingId === selectedBuildingId ? timeLeft : 0}
            imagePath={"buildings"}
          />
        </SCardWrapper>
      </div>
      <div className={"col-8"}>
        <SH2>{selectedBuilding?.title}</SH2>
        <div>
          {requiredResources?.length > 0 && (
            <>
              <SText>Required resources:</SText>
              <SParams>
                {requiredResources.map((resource) => {
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
                  <Icon title={"time"} /> {convertSecondsToTime(timeRequired)}
                </SParam>
              </SParams>
            </>
          )}
        </div>
        <div>
          {production && production.length > 0 && (
            <>
              <SText>It provides:</SText>

              <SParams>
                {production.map((bProduction) => {
                  const coefficient =
                    city?.resourcesProductionCoefficient?.find(
                      (production) =>
                        production.resourceId === bProduction.resourceId
                    )?.coefficient || 1;

                  return (
                    <SParam>
                      <Icon
                        title={getResourceSlug(
                          dictionaries.resourcesDictionary,
                          bProduction.resourceId
                        )}
                      />
                      {bProduction.qty * coefficient}
                    </SParam>
                  );
                })}
              </SParams>
            </>
          )}

          {hasRequirements("building", selectedBuildingId, nextLvl) && (
            <>
              <SText>It requires:</SText>
              {getRequirements("building", selectedBuildingId, nextLvl)?.map(
                (requirement, index) => {
                  const requiredItem = getRequiredItem(requirement);

                  return (
                    <SText key={index}>
                      {requiredItem?.title}, {requirement.requiredEntityLvl} lvl
                    </SText>
                  );
                }
              )}
            </>
          )}
        </div>
        <SButtonsBlock>
          {!isCurrentBuildingInProcess && (
            <button
              className={"btn btn-primary"}
              disabled={
                isBuildingDisabled() ||
                !hasAllRequirements("building", selectedBuildingId, nextLvl) ||
                (isSomeBuildingInProcess && !isCurrentBuildingInProcess)
              }
              onClick={() => {
                run(selectedBuildingId);
              }}
            >
              {!requiredResources?.length ? "Max Level" : "Build"}
            </button>
          )}

          {isCurrentBuildingInProcess && (
            <button
              className={"btn btn-warning"}
              onClick={() => {
                cancel(selectedBuildingId);
              }}
            >
              Cancel
            </button>
          )}
        </SButtonsBlock>
        <SText>{selectedBuilding?.description}</SText>
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
