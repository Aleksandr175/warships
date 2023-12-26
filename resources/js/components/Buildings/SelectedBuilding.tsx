import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime } from "../../utils";
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
  ICityResources,
  IResearch,
  IUserResearch,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";

interface IProps {
  selectedBuildingId: number;
  cityId: number;
  buildingsDictionary: IBuilding[];
  buildingDependencyDictionary: IBuildingDependency[];
  buildingResourcesDictionary: IBuildingResource[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  buildings: ICityBuilding[] | undefined;
  setBuildings: (buildings: ICityBuilding[]) => void;
  buildingsProduction?: IBuildingsProduction[];
  queue?: ICityBuildingQueue;
  setQueue: (q: ICityBuildingQueue | undefined) => void;
  researchesDictionary: IResearch[];
  researches: IUserResearch[];
  timeLeft: number;
  getLvl: (buildingId: number) => number;
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
}: IProps) => {
  const getResourcesForBuilding = (buildingId: number, lvl: number) => {
    return buildingResourcesDictionary.find(
      (br) => br.buildingId === buildingId && br.lvl === lvl
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

  const buildingResources = getResourcesForBuilding(
    selectedBuildingId,
    nextLvl
  );
  const gold = buildingResources?.gold || 0;
  const population = buildingResources?.population || 0;
  const time = buildingResources?.time || 0;

  const isBuildingDisabled = () => {
    return (
      gold > cityResources.gold ||
      population > cityResources.population ||
      !buildingResources
    );
  };

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
          {Boolean(gold || population) && (
            <>
              <SText>Required resources:</SText>
              <SParams>
                <SParam>
                  <Icon title={"gold"} /> {gold}
                </SParam>
                <SParam>
                  <Icon title={"worker"} /> {population}
                </SParam>
                <SParam>
                  <Icon title={"time"} /> {convertSecondsToTime(time)}
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
                (requirement) => {
                  const requiredItem = getRequiredItem(requirement);

                  return (
                    <SText>
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
              {!buildingResources ? "Max Level" : "Build"}
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
