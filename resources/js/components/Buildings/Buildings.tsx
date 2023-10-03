import React, { useEffect, useRef, useState } from "react";
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
import styled from "styled-components";
import { Building } from "./Building";
import {
  SButtonsBlock,
  SContent,
  SH1,
  SH2,
  SParam,
  SParams,
  SText,
} from "../styles";
import { Card } from "../Common/Card";
import { Icon } from "../Common/Icon";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { convertSecondsToTime, getTimeLeft } from "../../utils";
import { useSelectedBuildingRequirements } from "./hooks/useSelectedBuildingRequirements";
dayjs.extend(utc);

interface IProps {
  cityId: number;
  buildingsDictionary: IBuilding[];
  buildingDependencyDictionary: IBuildingDependency[];
  buildingResourcesDictionary: IBuildingResource[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  buildings: ICityBuilding[] | undefined;
  setBuildings: (buildings: ICityBuilding[]) => void;
  getBuildings: () => void;
  buildingsProduction?: IBuildingsProduction[];
  queue?: ICityBuildingQueue;
  setQueue: (q: ICityBuildingQueue | undefined) => void;
  researchDictionary: IResearch[];
  researches: IUserResearch[];
}

export const Buildings = ({
  buildings,
  setBuildings,
  getBuildings,
  cityId,
  buildingsDictionary,
  buildingResourcesDictionary,
  buildingDependencyDictionary,
  updateCityResources,
  cityResources,
  buildingsProduction,
  queue,
  setQueue,
  researchDictionary,
  researches,
}: IProps) => {
  const [selectedBuildingId, setSelectedBuildingId] = useState(0);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();

  useEffect(() => {
    setSelectedBuildingId(buildingsDictionary[0]?.id || 0);
  }, [buildingsDictionary]);

  const getLvl = (buildingId: number) => {
    const building = buildings?.find((b) => b.buildingId === buildingId);

    if (building) {
      return building.lvl;
    }

    return 0;
  };

  const getResourcesForBuilding = (buildingId: number, lvl: number) => {
    return buildingResourcesDictionary.find(
      (br) => br.buildingId === buildingId && br.lvl === lvl
    );
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

  const isBuildingInProcess = () => {
    return queue && queue.buildingId === selectedBuildingId;
  };

  const getBuilding = (buildingId: number): IBuilding | undefined => {
    return buildingsDictionary.find((building) => building.id === buildingId);
  };

  const selectedBuilding = getBuilding(selectedBuildingId);
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

  useEffect(() => {
    if (getTimeLeft(queue?.deadline || "")) {
      setTimeLeft(getTimeLeft(queue?.deadline || ""));

      // @ts-ignore
      timer.current = setInterval(handleTimer, 1000);

      return () => {
        clearInterval(timer.current);
      };
    } else {
      setTimeLeft(0);
    }
  }, [queue, selectedBuildingId]);

  useEffect(() => {
    // TODO strange decision
    if (timeLeft === -1) {
      clearInterval(timer.current);
      getBuildings();
    }
  }, [timeLeft]);

  const handleTimer = () => {
    setTimeLeft((lastTimeLeft) => {
      // @ts-ignore
      return lastTimeLeft - 1;
    });
  };

  const {
    hasRequirements,
    hasAllRequirements,
    getRequirements,
    getRequiredItem,
  } = useSelectedBuildingRequirements({
    buildingDependencyDictionary,
    buildingsDictionary,
    researchDictionary,
    buildings,
    researches,
  });

  return (
    <SContent>
      <SH1>Buildings</SH1>
      {selectedBuildingId && selectedBuilding && (
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
                getProductionResource("population")) && (
                <SText>It provides:</SText>
              )}
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

              {hasRequirements(selectedBuildingId, lvl) && (
                <>
                  <SText>It requires:</SText>
                  {getRequirements(selectedBuildingId, lvl).map(
                    (requirement) => {
                      const requiredItem = getRequiredItem(requirement);

                      return (
                        <SText>
                          {requiredItem?.title}, {requirement.requiredEntityLvl}{" "}
                          lvl
                        </SText>
                      );
                    }
                  )}
                </>
              )}
            </div>
            <SButtonsBlock>
              {!isBuildingInProcess() && (
                <button
                  className={"btn btn-primary"}
                  disabled={
                    isBuildingDisabled() ||
                    !hasAllRequirements(selectedBuildingId, nextLvl)
                  }
                  onClick={() => {
                    run(selectedBuildingId);
                  }}
                >
                  Build
                </button>
              )}

              {isBuildingInProcess() && (
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
      )}

      {buildingsProduction &&
        buildingsDictionary.map((item) => {
          const lvl = getLvl(item.id);
          const buildingResources = getResourcesForBuilding(item.id, nextLvl);
          const gold = buildingResources?.gold || 0;
          const population = buildingResources?.population || 0;

          return (
            <SItemWrapper
              onClick={() => {
                setSelectedBuildingId(item.id);
              }}
            >
              <Building
                lvl={lvl}
                key={item.id}
                building={item}
                gold={gold}
                population={population}
                run={run}
                cancel={cancel}
                queue={queue}
                timeLeft={
                  queue?.buildingId === item.id
                    ? getTimeLeft(queue?.deadline || "")
                    : 0
                }
                getBuildings={getBuildings}
                cityResources={cityResources}
                buildingsProduction={buildingsProduction}
                selected={selectedBuildingId === item.id}
              />
            </SItemWrapper>
          );
        })}
    </SContent>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;

const SSelectedItem = styled.div`
  margin-bottom: calc(var(--block-gutter-y) * 2);
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;
