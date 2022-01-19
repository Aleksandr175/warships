import React, { useEffect, useState } from "react";
import { httpClient } from "../httpClient/httpClient";
import { IBuilding, ICityBuilding } from "../types/types";

interface IProps {
    cityId: number;
    buildingsDictionary: IBuilding[];
}

export const Buildings = ({ cityId, buildingsDictionary }: IProps) => {
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

    return (
        <>
            {buildingsDictionary.map((item) => {
                return (
                    <div>
                        <h4>
                            {item.title} (Ур. {getLvl(item.id)})
                        </h4>
                        <p>{item.description}</p>

                        <button className={"btn btn-primary"}>Построить</button>
                    </div>
                );
            })}
        </>
    );
};
