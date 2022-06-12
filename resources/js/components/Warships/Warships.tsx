import React from "react";
import { httpClient } from "../../httpClient/httpClient";
import {
    IBuildingResource,
    ICityResources,
    ICityWarship,
    ICityWarshipQueue,
    IWarship,
} from "../../types/types";
import { Warship } from "./Warship";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { WarshipsQueue } from "./WarshipsQueue";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
    cityId: number;
    dictionary: IWarship[];
    resourcesDictionary: IBuildingResource[];
    updateCityResources: (cityResources: ICityResources) => void;
    cityResources: ICityResources;
    warships: ICityWarship[] | undefined;
    setWarships: (warships: ICityWarship[]) => void;
    getWarships: () => void;
    queue?: ICityWarshipQueue[];
    setQueue: (q: ICityWarshipQueue[] | undefined) => void;
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

    function getQty(warshipId: number): number {
        return (
            warships?.find((warship) => warship.warshipId === warshipId)?.qty ||
            0
        );
    }

    return (
        <div className={"row"}>
            {dictionary.map((item) => {
                return (
                    <Warship
                        key={item.id}
                        warship={item}
                        run={run}
                        currentQty={getQty(item.id)}
                        getWarships={getWarships}
                        cityResources={cityResources}
                    />
                );
            })}

            {queue && queue.length > 0 && (
                <WarshipsQueue
                    queue={queue}
                    dictionary={dictionary}
                    sync={getWarships}
                />
            )}
        </div>
    );
};
