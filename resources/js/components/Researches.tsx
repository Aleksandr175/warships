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
    IUserResearch,
} from "../types/types";
import styled from "styled-components";
import { Research } from "./Research";

interface IProps {
    cityId: number;
    dictionary: IResearch[];
    resourcesDictionary: IResearchResource[];
    updateCityResources: (cityResources: ICityResources) => void;
    cityResources: ICityResources;
    researches: IUserResearch[];
    /*setBuildings: (buildings: ICityBuilding[]) => void;
    getBuildings: () => void;
    queue?: ICityBuildingQueue;
    setQueue: (q: ICityBuildingQueue | undefined) => void;*/
}

export const Researches = ({
    cityId,
    dictionary,
    resourcesDictionary,
    updateCityResources,
    cityResources,
    researches,
}: /*queue,
    setQueue,*/
IProps) => {
    function getLvl(researchId: number) {
        const research = researches?.find((r) => r.researchId === researchId);

        if (research) {
            return research.lvl;
        }

        return 0;
    }

    function getResources(resourceId: number, lvl: number) {
        return resourcesDictionary.find(
            (rr) => rr.researchId === resourceId && rr.lvl === lvl
        );
    }

    function run(researchId: number) {
        httpClient
            .post("/researches/" + researchId + "/run")
            .then((response) => {
                /*setBuildings(response.data.buildings);
                setQueue(response.data.buildingQueue);*/
                updateCityResources(response.data.cityResources);
            });
    }

    function cancel(researchId: number) {
        httpClient
            .post("/researches/" + researchId + "/cancel")
            .then((response) => {
                /*setBuildings(response.data.buildings);
                setQueue(undefined);*/

                updateCityResources(response.data.cityResources);
            });
    }

    return (
        <div className={"row"}>
            {dictionary.map((item) => {
                const lvl = getLvl(item.id);
                const resources = getResources(item.id, lvl + 1);
                const gold = resources?.gold || 0;
                const population = resources?.population || 0;

                return (
                    <Research
                        lvl={lvl}
                        key={item.id}
                        research={item}
                        gold={gold}
                        population={population}
                        run={() => {} /*build*/}
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
