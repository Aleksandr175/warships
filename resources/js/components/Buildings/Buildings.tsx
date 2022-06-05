import React from "react";
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
    function getLvl(buildingId: number) {
        const building = buildings?.find((b) => b.buildingId === buildingId);

        if (building) {
            return building.lvl;
        }

        return 0;
    }

    function getResources(buildingId: number, lvl: number) {
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

    return (
        <div className={"row"}>
            {buildingsProduction &&
                buildingsDictionary.map((item) => {
                    const lvl = getLvl(item.id);
                    const buildingResources = getResources(item.id, lvl + 1);
                    const gold = buildingResources?.gold || 0;
                    const population = buildingResources?.population || 0;

                    return (
                        <Building
                            lvl={lvl}
                            key={item.id}
                            building={item}
                            gold={gold}
                            population={population}
                            run={run}
                            cancel={cancel}
                            queue={queue}
                            getBuildings={getBuildings}
                            cityResources={cityResources}
                            buildingsProduction={buildingsProduction}
                        />
                    );
                })}
        </div>
    );
};

const SBuildingImageWrapper = styled.div`
    border: 1px solid black;
    height: 200px;
    margin-bottom: 20px;
    position: relative;
    background: #ddd;
`;

const SBuildingLvlWrapper = styled.div`
    position: absolute;
    top: 0;
    right: 0;
    border: 30px solid transparent;
    border-top: 30px solid #ccc;
    border-right: 30px solid #ccc;
`;

const SBuildingLvl = styled.span`
    position: absolute;
    top: -25px;
    right: -20px;
    font-size: 16px;
    font-weight: 700;
`;
