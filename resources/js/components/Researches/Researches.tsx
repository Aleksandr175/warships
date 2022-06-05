import React from "react";
import { httpClient } from "../../httpClient/httpClient";
import {
    ICityResearchQueue,
    ICityResources,
    IResearch,
    IResearchResource,
    IUserResearch,
} from "../../types/types";
import { Research } from "./Research";

interface IProps {
    cityId: number;
    dictionary: IResearch[];
    resourcesDictionary: IResearchResource[];
    updateCityResources: (cityResources: ICityResources) => void;
    cityResources: ICityResources;
    researches: IUserResearch[];
    queue?: ICityResearchQueue;
    setQueue: (q: ICityResearchQueue | undefined) => void;
    /*setBuildings: (buildings: ICityBuilding[]) => void;
    getBuildings: () => void;
    */
}

export const Researches = ({
    cityId,
    dictionary,
    resourcesDictionary,
    updateCityResources,
    cityResources,
    researches,
    queue,
    setQueue,
}: IProps) => {
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
            .post("/researches/" + researchId + "/run", {
                cityId,
                researchId,
            })
            .then((response) => {
                //setResearches(response.data.buildings);
                setQueue(response.data.queue);
                updateCityResources(response.data.cityResources);
            });
    }

    function cancel(researchId: number) {
        httpClient
            .post("/researches/" + researchId + "/cancel")
            .then((response) => {
                /*setBuildings(response.data.buildings);*/
                setQueue(undefined);
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
                        run={run}
                        cancel={cancel}
                        queue={queue}
                        sync={() => {} /*getBuildings*/}
                        cityResources={cityResources}
                    />
                );
            })}
        </div>
    );
};
