import React, { useEffect, useState } from "react";
import { httpClient } from "../httpClient/httpClient";
import { IBuilding, IBuildingResource, ICityBuilding } from "../types/types";

interface IProps {
    cityId: number;
    buildingsDictionary: IBuilding[];
    buildingResourcesDictionary: IBuildingResource[];
}

export const Buildings = ({
    cityId,
    buildingsDictionary,
    buildingResourcesDictionary,
}: IProps) => {
    const [buildings, setBuildings] = useState<ICityBuilding[]>();

    useEffect(() => {
        httpClient.get("/buildings?cityId=" + cityId).then((response) => {
            setBuildings(response.data.data);
        });
    }, []);

    function getLvl(buildingId: number) {
        const building = buildings?.find((b) => b.id === buildingId);

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

    return (
        <>
            {buildingsDictionary.map((item) => {
                const lvl = getLvl(item.id);
                const buildingResources = getResources(item.id, lvl + 1);
                const gold = buildingResources?.gold || 0;
                const population = buildingResources?.population || 0;

                return (
                    <div key={item.id}>
                        <h4>
                            {item.title} (Ур. {lvl})
                        </h4>
                        <span>{item.description}</span>

                        {buildingResources && (
                            <>
                                <p>
                                    Золото: {gold}. Рабочие: {population}
                                </p>
                                <button className={"btn btn-primary"}>
                                    Построить
                                </button>
                            </>
                        )}

                        <br />
                        <br />
                    </div>
                );
            })}
        </>
    );
};
