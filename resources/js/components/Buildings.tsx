import React, { useEffect, useState } from "react";
import { httpClient } from "../httpClient/httpClient";
import { IBuilding, IBuildingResource, ICityBuilding } from "../types/types";
import styled from "styled-components";

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

    function build(buildingId: number) {
        httpClient
            .post("/build", {
                cityId,
                buildingId,
            })
            .then((response) => {
                console.log(response);
            });
    }

    return (
        <div className={"row"}>
            {buildingsDictionary.map((item) => {
                const lvl = getLvl(item.id);
                const buildingResources = getResources(item.id, lvl + 1);
                const gold = buildingResources?.gold || 0;
                const population = buildingResources?.population || 0;

                return (
                    <div className={"col-4"} key={item.id}>
                        <SBuildingImageWrapper>
                            <SBuildingLvlWrapper>
                                <SBuildingLvl>{lvl}</SBuildingLvl>
                            </SBuildingLvlWrapper>
                        </SBuildingImageWrapper>
                        <h4>{item.title}</h4>
                        <span>{item.description}</span>

                        {buildingResources && (
                            <>
                                <p>
                                    Золото: {gold}. Рабочие: {population}
                                </p>
                                <button
                                    className={"btn btn-primary"}
                                    onClick={() => {
                                        build(item.id);
                                    }}
                                >
                                    Построить
                                </button>
                            </>
                        )}

                        <br />
                        <br />
                    </div>
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
