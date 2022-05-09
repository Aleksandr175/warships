import React, { useEffect, useRef, useState } from "react";
import {
    IBuilding,
    IBuildingsProduction,
    ICityBuildingQueue,
    ICityResources,
} from "../types/types";
import styled from "styled-components";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
dayjs.extend(utc);

interface IProps {
    building: IBuilding;
    lvl: number;
    gold: number;
    population: number;
    build: (buildingId: number) => void;
    cancel: (buildingId: number) => void;
    queue: ICityBuildingQueue | undefined;
    getBuildings: () => void;
    cityResources: ICityResources;
    buildingsProduction: IBuildingsProduction[];
}

export const Building = ({
    building,
    lvl,
    gold,
    population,
    build,
    cancel,
    queue,
    getBuildings,
    cityResources,
    buildingsProduction,
}: IProps) => {
    const [timeLeft, setTimeLeft] = useState<number | null>(null);
    const timer = useRef();

    useEffect(() => {
        if (isBuildingInProcess() && getTimeLeft()) {
            setTimeLeft(getTimeLeft());

            // @ts-ignore
            timer.current = setInterval(handleTimer, 1000);

            return () => {
                clearInterval(timer.current);
            };
        }
    }, [queue]);

    useEffect(() => {
        if (timeLeft === 0) {
            clearInterval(timer.current);
            getBuildings();
        }
    }, [timeLeft]);

    function handleTimer() {
        setTimeLeft((lastTimeLeft) => {
            return lastTimeLeft ? lastTimeLeft - 1 : 0;
        });
    }

    function isBuildingInProcess() {
        return queue && queue.id === building.id;
    }

    function getTimeLeft() {
        const dateUTCNow = dayjs.utc(new Date());
        let deadline = dayjs(new Date(queue?.deadline || ""));

        let deadlineString = deadline.format().toString().replace("T", " ");
        let dateArray = deadlineString.split("+");
        const deadlineDate = dateArray[0];

        return dayjs.utc(deadlineDate).unix() - dateUTCNow.unix();
    }

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
        <div className={"col-sm-6 col-md-4"} key={building.id}>
            <SBuildingImageWrapper
                style={{
                    backgroundImage: `url("../images/buildings/${building.id}.svg")`,
                }}
            >
                <SBuildingLvlWrapper>
                    <SBuildingLvl>{lvl}</SBuildingLvl>
                </SBuildingLvlWrapper>
            </SBuildingImageWrapper>
            <h4>{building.title}</h4>
            <span>{building.description}</span>

            {(gold || population) &&
            !isBuildingInProcess() &&
            !Boolean(queue && queue.id) ? (
                <>
                    <p>
                        Золото: {gold}. Рабочие: {population}
                    </p>
                    {getProductionResource("population") ? (
                        <p>
                            Прирост населения:{" "}
                            {getProductionResource("population")}
                        </p>
                    ) : (
                        ""
                    )}
                    {getProductionResource("gold") ? (
                        <p>
                            Добыча золота / час: {getProductionResource("gold")}
                        </p>
                    ) : (
                        ""
                    )}
                    <button
                        className={"btn btn-primary"}
                        disabled={isBuildingDisabled()}
                        onClick={() => {
                            build(building.id);
                        }}
                    >
                        Построить
                    </button>
                </>
            ) : (
                ""
            )}

            {(gold || population) && isBuildingInProcess() ? (
                <>
                    <p>Окончание через: {timeLeft} сек.</p>
                    <p>
                        Золото: {gold}. Рабочие: {population}
                    </p>
                    <button
                        className={"btn btn-warning"}
                        onClick={() => {
                            cancel(building.id);
                        }}
                    >
                        Отменить
                    </button>
                </>
            ) : (
                ""
            )}

            <br />
            <br />
        </div>
    );
};

const SBuildingImageWrapper = styled.div`
    border: 1px solid black;
    height: 100px;
    margin-bottom: 20px;
    position: relative;

    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #ddd;
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