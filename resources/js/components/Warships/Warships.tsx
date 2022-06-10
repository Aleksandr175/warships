import React from "react";
import { httpClient } from "../../httpClient/httpClient";
import {
    IBuildingResource,
    ICityBuildingQueue,
    ICityResources,
    ICityWarship,
    IWarship,
} from "../../types/types";
import { Warship } from "./Warship";

interface IProps {
    cityId: number;
    dictionary: IWarship[];
    resourcesDictionary: IBuildingResource[];
    updateCityResources: (cityResources: ICityResources) => void;
    cityResources: ICityResources;
    warships: ICityWarship[] | undefined;
    setWarships: (warships: ICityWarship[]) => void;
    getWarships: () => void;
    queue?: ICityBuildingQueue;
    setQueue: (q: ICityBuildingQueue | undefined) => void;
}

export const Warships = ({
    warships,
    setWarships,
    getWarships,
    cityId,
    dictionary,
    updateCityResources,
    cityResources,
    queue,
    setQueue,
}: IProps) => {
    function run(warshipId: number, qty: number) {
        httpClient
            .post("/warships/create", {
                cityId,
                warshipId,
                qty,
            })
            .then((response) => {
                setWarships(response.data.warships);
                setQueue(response.data.queue);
                updateCityResources(response.data.cityResources);
            });
    }

    return (
        <div className={"row"}>
            {dictionary.map((item) => {
                return (
                    <Warship
                        key={item.id}
                        warship={item}
                        run={run}
                        currentQty={100}
                        queue={queue}
                        getWarships={getWarships}
                        cityResources={cityResources}
                    />
                );
            })}
        </div>
    );
};
