import React, { useEffect, useRef, useState } from "react";
import {
  ICityFleet,
  IDictionary,
  IFleetWarshipsData,
  IFleetIncoming,
  IMapCity,
} from "../types/types";
import styled, { css } from "styled-components";
import { Icon } from "./Common/Icon";
import { convertSecondsToTime, getTimeLeft } from "../utils";
import { FleetWarships } from "./Common/FleetWarships";
import { useFetchDictionaries } from "../hooks/useFetchDictionaries";

export const Fleet = ({
  fleet,
  fleetCities,
  fleetDetails,
}: {
  fleet: ICityFleet | IFleetIncoming;
  fleetCities: IMapCity[];
  fleetDetails: IFleetWarshipsData[];
}) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const [timeLeft, setTimeLeft] = useState<number | null>(null);
  const timer = useRef();

  useEffect(() => {
    setTimeLeft(getTimeLeft(fleet?.deadline || ""));

    // @ts-ignore
    timer.current = setInterval(handleTimer, 1000);

    return () => {
      clearInterval(timer.current);
    };
  }, [fleet]);

  function handleTimer() {
    setTimeLeft((lastTimeLeft) => {
      return lastTimeLeft ? lastTimeLeft - 1 : 0;
    });
  }

  useEffect(() => {
    if (timeLeft === -1) {
      setTimeLeft(0);
    }
  }, [timeLeft]);

  const getFleetTaskIconName = (fleetTaskId: number): string => {
    const fleetTask = dictionaries?.fleetTasksDictionary.find(
      (task) => fleetTaskId === task.id
    );

    if (fleetTask?.slug) {
      if (fleetTask.slug === "trade") {
        return "directions";
      }
      if (fleetTask.slug === "move") {
        return "pin-2";
      }
      if (fleetTask.slug === "attack") {
        return "attack";
      }
      if (fleetTask.slug === "transport") {
        return "refresh";
      }
      if (fleetTask.slug === "expedition") {
        return "i-expedition";
      }
    }

    return "";
  };

  const getFleetStatusTitle = (fleetStatusId: number): string => {
    const fleetStatus = dictionaries?.fleetStatusesDictionary.find(
      (status) => fleetStatusId === status.id
    );

    return fleetStatus?.title || "";
  };

  const getCityName = (cityId: number): string => {
    return fleetCities.find((city) => city.id === cityId)?.title || "";
  };

  const getCityCoords = (cityId: number): string => {
    const city = fleetCities.find((city) => city.id === cityId);

    return city ? city.coordY + ":" + city.coordX : "unknown";
  };

  return (
    <SFleetRow>
      <SFleetRowTitle>
        <SFleetInfo>
          <SFleetTaskIcon>
            <Icon title={getFleetTaskIconName(fleet.fleetTaskId)} />
            {fleet?.repeating ? "R" : ""}
          </SFleetTaskIcon>
          <SFleetDestinations>
            <SCityName>
              {getCityName(fleet.cityId)} [{getCityCoords(fleet.cityId)}]
            </SCityName>
            <SCityNameMine>
              {getCityName(fleet.targetCityId)} [
              {getCityCoords(fleet.targetCityId)}]
            </SCityNameMine>
          </SFleetDestinations>
        </SFleetInfo>
        <SFleetInfoSecond>
          <div>
            {timeLeft ? (
              <>
                <Icon title={"speed"} />
                <span>{convertSecondsToTime(timeLeft)}</span>
              </>
            ) : (
              <>Processing</>
            )}
          </div>
          <div>
            <>
              <Icon title={"pin"} size={"small"} />
              <span>{getFleetStatusTitle(fleet.fleetStatusId)}</span>
            </>
          </div>
        </SFleetInfoSecond>
      </SFleetRowTitle>

      <FleetWarships warships={fleetDetails} />
    </SFleetRow>
  );
};

const SFleetRow = styled.div`
  margin-bottom: 20px;
`;

const SFleetRowTitle = styled.div`
  display: flex;
  align-items: center;
  justify-content: flex-start;
  margin-bottom: 5px;
`;

const SFleetTaskIcon = styled.div<{ type?: string }>``;

const SCityName = styled.div`
  width: 150px;
  text-overflow: ellipsis;
`;

const SCityNameMine = styled.div`
  opacity: 0.4;
`;

const SFleetInfo = styled.div`
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
`;
const SFleetDestinations = styled.div``;

const SFleetInfoSecond = styled.div`
  width: 100px;

  span {
    display: inline-block;
    margin-left: 5px;
    opacity: 0.4;
  }
`;
