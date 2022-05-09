import React from "react";
import { httpClient } from "../httpClient/httpClient";
import {
    IBuilding,
    IBuildingResource,
    ICityBuilding,
    ICityBuildingQueue,
    ICityResources,
    IResearch,
    IResearchResource,
} from "../types/types";
import styled from "styled-components";
import { Research } from "./Research";

interface IProps {
    cityId: number;
    dictionary: IResearch[];
    resourcesDictionary: IResearchResource[];
    updateCityResources: (cityResources: ICityResources) => void;
    cityResources: ICityResources;
    /*setBuildings: (buildings: ICityBuilding[]) => void;
    getBuildings: () => void;
    queue?: ICityBuildingQueue;
    setQueue: (q: ICityBuildingQueue | undefined) => void;*/
}

export const Researches = ({
    /*setBuildings,
    getBuildings,*/
    cityId,
    dictionary,
    resourcesDictionary,
    updateCityResources,
    cityResources,
}: /*queue,
    setQueue,*/
IProps) => {
    /*function getLvl(researchId: number) {
        const research = dictionary?.find((r) => r.id === researchId);

        if (research) {
            return building.lvl;
        }

        return 0;
    }*/

    function getResources(resourceId: number, lvl: number) {
        return resourcesDictionary.find(
            (rr) => rr.researchId === resourceId && rr.lvl === lvl
        );
    }

    /*function build(buildingId: number) {
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
    }*/

    /*function cancel(buildingId: number) {
        httpClient
            .post("/build/" + buildingId + "/cancel", {
                cityId,
            })
            .then((response) => {
                setBuildings(response.data.buildings);
                setQueue(undefined);

                updateCityResources(response.data.cityResources);
            });
    }*/

    return (
        <div className={"row"}>
            {dictionary.map((item) => {
                const lvl = 0; //getLvl(item.id);
                const buildingResources = getResources(item.id, lvl + 1);
                const gold = buildingResources?.gold || 0;
                const population = buildingResources?.population || 0;

                return (
                    <Research
                        lvl={lvl}
                        key={item.id}
                        research={item}
                        gold={gold}
                        population={population}
                        build={() => {} /*build*/}
                        cancel={() => {} /*cancel*/}
                        queue={() => {} /*queue*/}
                        getBuildings={() => {} /*getBuildings*/}
                        cityResources={cityResources}
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
