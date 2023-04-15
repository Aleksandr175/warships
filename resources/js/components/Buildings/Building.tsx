import React, { useEffect, useRef, useState } from "react";
import {
    IBuilding,
    IBuildingsProduction,
    ICityBuildingQueue,
    ICityResources,
} from "../../types/types";
import styled from "styled-components";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { Card } from "../Common/Card";
dayjs.extend(utc);

interface IProps {
    building: IBuilding;
    lvl: number;
    gold: number;
    population: number;
    run: (buildingId: number) => void;
    cancel: (buildingId: number) => void;
    queue: ICityBuildingQueue | undefined;
    getBuildings: () => void;
    cityResources: ICityResources;
    buildingsProduction: IBuildingsProduction[];
    timeLeft: number;
}

export const Building = ({
    building,
    lvl,
    gold,
    population,
    run,
    cancel,
    queue,
    getBuildings,
    cityResources,
    buildingsProduction,
    timeLeft,
}: IProps) => {
    function isBuildingDisabled() {
        return (
            gold > cityResources.gold || population > cityResources.population
        );
    }

    function getProductionResource(resource: "population" | "gold") {
        const production = buildingsProduction.find((bProduction) => {
            return (
                bProduction.buildingId === building.id &&
                bProduction.lvl === lvl + 1 &&
                bProduction.resource === resource
            );
        });

        return production?.qty;
    }

    return (
        <SBuilding key={building.id}>
            <Card
                object={building}
                qty={lvl}
                imagePath={"buildings"}
                timer={timeLeft}
            />
        </SBuilding>
    );
};

const SBuilding = styled.div`
    border-radius: var(--block-border-radius-small);
    width: 140px;
    display: inline-block;
    margin-right: calc(var(--block-gutter-x) / 2);
    margin-bottom: calc(var(--block-gutter-y) / 2);
`;
