import React, { useEffect, useRef, useState } from "react";
import { httpClient } from "../../httpClient/httpClient";
import {
  IBuilding,
  IBuildingResource,
  IBuildingsProduction,
  ICityBuilding,
  ICityBuildingQueue,
  ICityResources,
} from "../../types/types";
import styled from "styled-components";
import { Building } from "./Building";
import { SH1, SH2, SText } from "../styles";
import { Card } from "../Common/Card";
import { Icon } from "../Common/Icon";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { convertSecondsToTime, getTimeLeft } from "../../utils";
dayjs.extend(utc);

interface IProps {
  cityId: number;
  buildingsDictionary: IBuilding[];
  buildingResourcesDictionary: IBuildingResource[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  buildings: ICityBuilding[] | undefined;
  setBuildings: (buildings: ICityBuilding[]) => void;
  getBuildings: () => void;
  buildingsProduction?: IBuildingsProduction[];
  queue?: ICityBuildingQueue;
  setQueue: (q: ICityBuildingQueue | undefined) => void;
}

export const Buildings = ({
  buildings,
  setBuildings,
  getBuildings,
  cityId,
  buildingsDictionary,
  buildingResourcesDictionary,
  updateCityResources,
  cityResources,
  buildingsProduction,
  queue,
  setQueue,
}: IProps) => {
  const [selectedBuildingId, setSelectedBuildingId] = useState(0);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();

  useEffect(() => {
    setSelectedBuildingId(buildingsDictionary[0]?.id || 0);
  }, [buildingsDictionary]);

  function getLvl(buildingId: number) {
    const building = buildings?.find((b) => b.buildingId === buildingId);

    if (building) {
      return building.lvl;
    }

    return 0;
  }

  function getResourcesForBuilding(buildingId: number, lvl: number) {
    return buildingResourcesDictionary.find(
      (br) => br.buildingId === buildingId && br.lvl === lvl
    );
  }

  function run(buildingId: number) {
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
  }

  function cancel(buildingId: number) {
    httpClient
      .post("/build/" + buildingId + "/cancel", {
        cityId,
      })
      .then((response) => {
        setBuildings(response.data.buildings);
        setQueue(undefined);

        updateCityResources(response.data.cityResources);
      });
  }

  function isBuildingInProcess() {
    return queue && queue.buildingId === selectedBuildingId;
  }

  function getBuilding(buildingId: number): IBuilding | undefined {
    return buildingsDictionary.find((building) => building.id === buildingId);
  }

  const selectedBuilding = getBuilding(selectedBuildingId);
  const lvl = getLvl(selectedBuildingId);
  const buildingResources = getResourcesForBuilding(
    selectedBuildingId,
    lvl + 1
  );
  const gold = buildingResources?.gold || 0;
  const population = buildingResources?.population || 0;
  const time = buildingResources?.time || 0;

  function isBuildingDisabled() {
    return (
      gold > cityResources.gold ||
      population > cityResources.population ||
      !buildingResources
    );
  }

  function getProductionResource(resource: "population" | "gold") {
    const production = buildingsProduction?.find((bProduction) => {
      return (
        bProduction.buildingId === selectedBuildingId &&
        bProduction.lvl === lvl + 1 &&
        bProduction.resource === resource
      );
    });

    return production?.qty;
  }

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

  function handleTimer() {
    setTimeLeft((lastTimeLeft) => {
      // @ts-ignore
      return lastTimeLeft - 1;
    });
  }

  return (
    <div>
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
                  <Icon title={"gold"} /> {gold}
                  <Icon title={"worker"} /> {population}
                  <Icon title={"time"} /> {convertSecondsToTime(time)}
                </>
              )}
            </div>
            <div>
              {(getProductionResource("gold") ||
                getProductionResource("population")) && (
                <SText>It provides:</SText>
              )}
              {getProductionResource("gold") ? (
                <span>
                  <Icon title={"gold"} />
                  {getProductionResource("gold")}
                </span>
              ) : (
                ""
              )}
              {getProductionResource("population") ? (
                <span>
                  <Icon title={"worker"} />
                  {getProductionResource("population")}
                </span>
              ) : (
                ""
              )}
            </div>
            <br />
            {!isBuildingInProcess() && (
              <button
                className={"btn btn-primary"}
                disabled={isBuildingDisabled()}
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
            <br />
            <br />
            <SText>{selectedBuilding?.description}</SText>
          </div>
        </SSelectedItem>
      )}

      {buildingsProduction &&
        buildingsDictionary.map((item) => {
          const lvl = getLvl(item.id);
          const buildingResources = getResourcesForBuilding(item.id, lvl + 1);
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
    </div>
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
