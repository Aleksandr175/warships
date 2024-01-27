import React, { useEffect, useRef, useState } from "react";
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
import styled from "styled-components";
import { Building } from "./Building";
import { SContent, SH1 } from "../styles";
import { getTimeLeft } from "../../utils";
import { SelectedBuilding } from "./SelectedBuilding";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";

interface IProps {
  cityId: number;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  buildings: ICityBuilding[] | undefined;
  setBuildings: (buildings: ICityBuilding[]) => void;
  getBuildings: () => void;
  queue?: ICityBuildingQueue;
  setQueue: (q: ICityBuildingQueue | undefined) => void;
  researches: IUserResearch[];
}

export const Buildings = ({
  buildings,
  setBuildings,
  getBuildings,
  cityId,
  updateCityResources,
  cityResources,
  queue,
  setQueue,
  researches,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const [selectedBuildingId, setSelectedBuildingId] = useState(0);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();

  useEffect(() => {
    if (dictionaries) {
      setSelectedBuildingId(dictionaries.buildings[0]?.id || 0);
    }
  }, [dictionaries]);

  const getLvl = (buildingId: number): number => {
    const building = buildings?.find((b) => b.buildingId === buildingId);

    if (building) {
      return building.lvl;
    }

    return 0;
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

  if (!dictionaries) {
    return null;
  }

  return (
    <SContent>
      <SH1>Buildings</SH1>
      {selectedBuildingId && (
        <SelectedBuilding
          selectedBuildingId={selectedBuildingId}
          buildings={buildings}
          setBuildings={setBuildings}
          cityResources={cityResources}
          getLvl={getLvl}
          cityId={cityId}
          updateCityResources={updateCityResources}
          setQueue={setQueue}
          researches={researches}
          timeLeft={timeLeft}
          queue={queue}
        />
      )}

      {dictionaries.buildings.map((item) => {
        const lvl = getLvl(item.id);

        return (
          <SItemWrapper
            key={item.id}
            onClick={() => {
              setSelectedBuildingId(item.id);
            }}
          >
            <Building
              lvl={lvl}
              key={item.id}
              building={item}
              timeLeft={
                queue?.buildingId === item.id
                  ? getTimeLeft(queue?.deadline || "")
                  : 0
              }
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
