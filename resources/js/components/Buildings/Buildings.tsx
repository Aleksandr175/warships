import React, { useEffect, useRef, useState } from "react";
import { ICity, IUserResearch } from "../../types/types";
import styled from "styled-components";
import { Building } from "./Building";
import { SContent, SH1 } from "../styles";
import { getTimeLeft } from "../../utils";
import { SelectedBuilding } from "./SelectedBuilding";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useBuildings } from "../hooks/useBuildings";

interface IProps {
  city: ICity;
  researches: IUserResearch[];
}

export const Buildings = ({ city, researches }: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const { buildings, buildingQueue } = useBuildings({ cityId: city.id });

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
    if (getTimeLeft(buildingQueue?.deadline || "")) {
      setTimeLeft(getTimeLeft(buildingQueue?.deadline || ""));

      // @ts-ignore
      timer.current = setInterval(handleTimer, 1000);

      return () => {
        clearInterval(timer.current);
      };
    } else {
      setTimeLeft(0);
    }
  }, [buildingQueue, selectedBuildingId]);

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
          getLvl={getLvl}
          city={city}
          researches={researches}
          timeLeft={timeLeft}
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
                buildingQueue?.buildingId === item.id
                  ? getTimeLeft(buildingQueue?.deadline || "")
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
