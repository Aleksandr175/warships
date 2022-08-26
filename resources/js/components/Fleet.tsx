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

    const getWarshipTitle = (warshipId: number): string => {
        const warship = dictionaries.warships.find(
            (warship) => warship.id === warshipId
        );

        return warship ? warship.title : "";
    };

    return (
        <SFleetRow>
            <SFleetRowTitle>
                <SFleetTaskIcon type={getFleetTaskSlug(fleet.fleetTaskId)}>
                    {getFleetTaskSlug(fleet.fleetTaskId)[0].toUpperCase()}
                </SFleetTaskIcon>
                <div>
                    {getCityName(fleet.cityId)}, {getCityCoords(fleet.cityId)}
                </div>
                <div>{" -> "}</div>
                <div>
                    {getCityName(fleet.targetCityId)},{" "}
                    {getCityCoords(fleet.targetCityId)}
                </div>
                <SFleetTimer>
                    {timeLeft ? (
                        <>Time Left: {timeLeft} sec.</>
                    ) : (
                        <>Processing...</>
                    )}
                </SFleetTimer>
                <SFleetStatus>
                    {getFleetStatusTitle(fleet.fleetStatusId)}
                </SFleetStatus>
            </SFleetRowTitle>
            <div>
                {fleetDetails.map((fDetails) => {
                    return (
                        <div>
                            {getWarshipTitle(fDetails.warshipId)}:{" "}
                            {fDetails.qty}
                        </div>
                    );
                })}
            </div>
        </SFleetRow>
    );
};

const SFleetRow = styled.div``;

const SFleetRowTitle = styled.div`
    display: flex;
    align-items: center;
`;

const SFleetTaskIcon = styled.div<{ type?: string }>`
    width: 20px;
    height: 20px;
    text-align: center;
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
`;

const SFleetStatus = styled.div`
    margin-left: 10px;
`;
