import React, { useEffect, useRef, useState } from "react";
import {
  ICityFleet,
  IDictionary,
  IFleetDetail,
  IMapCity,
} from "../types/types";
import dayjs from "dayjs";
import styled, { css } from "styled-components";

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

  const getFleetTaskSlug = (fleetTaskId: number): string => {
    const fleetTask = dictionaries.fleetTasksDictionary.find(
      (task) => fleetTaskId === task.id
    );

    return fleetTask?.slug || "";
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
        <SFleetTaskIcon type={getFleetTaskSlug(fleet.fleetTaskId)}>
          {getFleetTaskSlug(fleet.fleetTaskId)[0].toUpperCase()}{" "}
          {fleet.repeating ? " R" : ""}
        </SFleetTaskIcon>
        <SCityName>
          {getCityCoords(fleet.cityId)}, {getCityName(fleet.cityId)}
        </SCityName>
        <SArrow>{" -> "}</SArrow>
        <SCityName>
          {getCityCoords(fleet.targetCityId)}, {getCityName(fleet.targetCityId)}
        </SCityName>
        <SFleetTimer>
          {timeLeft ? <>Time Left: {timeLeft} sec.</> : <>Processing...</>}
        </SFleetTimer>
        <SFleetStatus>{getFleetStatusTitle(fleet.fleetStatusId)}</SFleetStatus>
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
`;

const SFleetTaskIcon = styled.div<{ type?: string }>`
  display: flex;
  align-items: center;
  text-align: center;
  width: 25px;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
  color: white;
  margin-right: 10px;
  background-color: white;

  ${({ type }) =>
    type === "trade"
      ? css`
          background-color: #166741;
        `
      : ""};

  ${({ type }) =>
    type === "move"
      ? css`
          background-color: #033b64;
        `
      : ""};

  ${({ type }) =>
    type === "attack"
      ? css`
          background-color: #5e0505;
        `
      : ""};

  ${({ type }) =>
    type === "transport"
      ? css`
          background-color: #2cc27a;
        `
      : ""};
`;

const SFleetTimer = styled.div`
  margin-left: 10px;
  width: 200px;
`;

const SFleetStatus = styled.div`
  margin-left: 10px;
`;

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

  width: 40px;
  height: 24px;
`;

const SFleetDetail = styled.div`
  display: flex;
  align-items: center;
  margin-right: 20px;
  font-weight: bold;
`;

const SCityName = styled.div`
  width: 150px;
  text-overflow: ellipsis;
`;

const SArrow = styled.div`
  font-weight: 700;
  margin-right: 20px;
`;
