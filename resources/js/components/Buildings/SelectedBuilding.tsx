import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime, getResourceSlug } from "../../utils";
import React from "react";
import styled from "styled-components";
import { httpClient } from "../../httpClient/httpClient";
import {
  IBuilding,
  IBuildingDependency,
  IBuildingResource,
  IBuildingsProduction,
  ICityBuilding,
  ICityBuildingQueue,
  ICityResource,
  IResearch,
  IResourceDictionary,
  IUserResearch,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";

interface IProps {
  selectedBuildingId: number;
  cityId: number;
  buildingsDictionary: IBuilding[];
  buildingDependencyDictionary: IBuildingDependency[];
  buildingResourcesDictionary: IBuildingResource[];
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  buildings: ICityBuilding[] | undefined;
  setBuildings: (buildings: ICityBuilding[]) => void;
  buildingsProduction?: IBuildingsProduction[];
  queue?: ICityBuildingQueue;
  setQueue: (q: ICityBuildingQueue | undefined) => void;
  researchesDictionary: IResearch[];
  researches: IUserResearch[];
  timeLeft: number;
  getLvl: (buildingId: number) => number;
  resourcesDictionary: IResourceDictionary[];
}

export const SelectedBuilding = ({
  selectedBuildingId,
  buildings,
  setBuildings,
  cityId,
  buildingsDictionary,
  buildingDependencyDictionary,
  updateCityResources,
  cityResources,
  buildingsProduction,
  queue,
  setQueue,
  researchesDictionary,
  researches,
  timeLeft,
  buildingResourcesDictionary,
  getLvl,
  resourcesDictionary,
}: IProps) => {
  const getResourcesForBuilding = (buildingId: number, lvl: number) => {
    return (
      buildingResourcesDictionary.filter(
        (br) => br.buildingId === buildingId && br.lvl === lvl
      ) || []
    );
  };

  const getBuilding = (buildingId: number): IBuilding | undefined => {
    return buildingsDictionary.find((building) => building.id === buildingId);
  };

  const run = (buildingId: number) => {
    httpClient
      .post("/build", {
        cityId,
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
        cityId,
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
    dependencyDictionary: buildingDependencyDictionary,
    buildingsDictionary,
    researchesDictionary,
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

    return false;
  };

  // TODO: refactor it?
  const getProductionResource = (resource: "population" | "gold") => {
    const production = buildingsProduction?.find((bProduction) => {
      return (
        bProduction.buildingId === selectedBuildingId &&
        bProduction.lvl === nextLvl &&
        bProduction.resource === resource
      );
    });

    return production?.qty;
  };

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
                          resourcesDictionary,
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
          {(getProductionResource("gold") ||
            getProductionResource("population")) && <SText>It provides:</SText>}
          <SParams>
            {getProductionResource("gold") ? (
              <SParam>
                <Icon title={"gold"} />
                {getProductionResource("gold")}
              </SParam>
            ) : (
              ""
            )}
            {getProductionResource("population") ? (
              <SParam>
                <Icon title={"worker"} />
                {getProductionResource("population")}
              </SParam>
            ) : (
              ""
            )}
          </SParams>

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
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;
