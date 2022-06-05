import React, { useEffect, useRef, useState } from "react";
import {
    IBuilding,
    IBuildingsProduction,
    ICityBuildingQueue,
    ICityResources,
} from "../../types/types";
import styled from "styled-components";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
dayjs.extend(utc);

interface IProps {
    building: IBuilding;
    lvl: number;
    gold: number;
    population: number;
    run: (buildingId: number) => void;
    cancel: (buildingId: number) => void;
    queue: ICityBuildingQueue | undefined;
    getBuildings: () => void;
    cityResources: ICityResources;
    buildingsProduction: IBuildingsProduction[];
}

export const Warship = ({
    building,
    lvl,
    gold,
    population,
    run,
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
        // TODO strange decision
        if (timeLeft === -1) {
            clearInterval(timer.current);
            getBuildings();
        }
    }, [timeLeft]);

    function handleTimer() {
        setTimeLeft((lastTimeLeft) => {
            // @ts-ignore
            return lastTimeLeft - 1;
        });
    }

    function isBuildingInProcess() {
        return queue && queue.buildingId === building.id;
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
                    backgroundImage: `url("../images/warships/${building.id}.svg")`,
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
            !Boolean(queue && queue.buildingId) ? (
                <>
                    <p>
                        Gold: {gold}. Workers: {population}
                    </p>
                    {getProductionResource("population") ? (
                        <p>
                            Population growth:{" "}
                            {getProductionResource("population")}
                        </p>
                    ) : (
                        ""
                    )}
                    {getProductionResource("gold") ? (
                        <p>
                            Gold production / hour:{" "}
                            {getProductionResource("gold")}
                        </p>
                    ) : (
                        ""
                    )}
                    <button
                        className={"btn btn-primary"}
                        disabled={isBuildingDisabled()}
                        onClick={() => {
                            run(building.id);
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
                    <p>Ending in: {timeLeft} sec.</p>
                    <p>
                        Gold: {gold}. Workers: {population}
                    </p>
                    <button
                        className={"btn btn-warning"}
                        onClick={() => {
                            cancel(building.id);
                        }}
                    >
                        Cancel
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
