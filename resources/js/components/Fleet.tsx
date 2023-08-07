import React, { useEffect, useRef, useState } from "react";
import {
  ICityFleet,
  IDictionary,
  IFleetDetail,
  IMapCity,
} from "../types/types";
import dayjs from "dayjs";
import styled, { css } from "styled-components";
import { Icon } from "./Common/Icon";
import { convertSecondsToTime } from "../utils";

export const Fleet = ({
  fleet,
  dictionaries,
  fleetCities,
  fleetDetails,
}: {
  fleet: ICityFleet;
  dictionaries: IDictionary;
  fleetCities: IMapCity[];
  fleetDetails: IFleetDetail[];
}) => {
  const [timeLeft, setTimeLeft] = useState<number | null>(null);
  const timer = useRef();

  useEffect(() => {
    setTimeLeft(getTimeLeft());

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

  function getTimeLeft() {
    const dateUTCNow = dayjs.utc(new Date());
    let deadline = dayjs(new Date(fleet?.deadline || ""));

    let deadlineString = deadline.format().toString().replace("T", " ");
    let dateArray = deadlineString.split("+");
    const deadlineDate = dateArray[0];

    return dayjs.utc(deadlineDate).unix() - dateUTCNow.unix();
  }

  const getFleetTaskIconName = (fleetTaskId: number): string => {
    const fleetTask = dictionaries.fleetTasksDictionary.find(
      (task) => fleetTaskId === task.id
    );

    if (fleetTask?.slug) {
      if (fleetTask.slug === "trade") {
        return "refresh";
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
    }

    return "";
  };

  const getFleetStatusTitle = (fleetStatusId: number): string => {
    const fleetStatus = dictionaries.fleetStatusesDictionary.find(
      (status) => fleetStatusId === status.id
    );

    return fleetStatus?.title || "";
  };

  const getCityName = (cityId: number): string => {
    return fleetCities.find((city) => city.id === cityId)?.title || "";
  };

  const getCityCoords = (cityId: number): string => {
    const city = fleetCities.find((city) => city.id === cityId);

    return city ? city.coordY + ":" + city.coordX : "";
  };

  return (
    <SFleetRow>
      <SFleetRowTitle>
        <SFleetInfo>
          <SFleetTaskIcon>
            <Icon title={getFleetTaskIconName(fleet.fleetTaskId)} />
            {fleet.repeating ? "R" : ""}
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
                <Icon title={"clock"} size={"small"} />
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
      <SFleetDetails>
        {fleetDetails.map((fDetails) => {
          return (
            <SFleetDetail>
              <SWarshipIcon
                style={{
                  backgroundImage: `url("../images/warships/simple/${fDetails.warshipId}.svg")`,
                }}
              />
              <span>{fDetails.qty}</span>
            </SFleetDetail>
          );
        })}
      </SFleetDetails>
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

const SFleetTimer = styled.div``;

const SFleetStatus = styled.div``;

const SFleetDetails = styled.div`
  display: flex;
  font-size: 12px;
`;

const SWarshipIcon = styled.div`
  display: inline-block;
  background-size: contain;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  margin-right: 10px;

  width: 20px;
  height: 15px;
`;

const SFleetDetail = styled.div`
  display: flex;
  align-items: center;
  margin-right: 10px;
  font-weight: bold;
`;

const SCityName = styled.div`
  width: 150px;
  text-overflow: ellipsis;
`;

const SCityNameMine = styled.div`
  opacity: 0.4;
`;

const SArrow = styled.div`
  font-weight: 700;
  margin-right: 20px;
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
