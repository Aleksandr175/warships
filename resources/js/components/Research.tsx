import React, { useEffect, useRef, useState } from "react";
import {
    IBuilding,
    ICityBuildingQueue,
    ICityResearchQueue,
    ICityResources,
    IResearch,
} from "../types/types";
import styled from "styled-components";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
dayjs.extend(utc);

interface IProps {
    research: IResearch;
    lvl: number;
    gold: number;
    population: number;
    run: (researchId: number) => void;
    cancel: (researchId: number) => void;
    queue: ICityResearchQueue | undefined;
    sync: () => void;
    cityResources: ICityResources;
}

export const Research = ({
    research,
    lvl,
    gold,
    population,
    run,
    cancel,
    queue,
    sync,
    cityResources,
}: IProps) => {
    const [timeLeft, setTimeLeft] = useState<number | null>(null);
    const timer = useRef();

    useEffect(() => {
        if (isResearchInProcess() && getTimeLeft()) {
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
            sync();
        }
    }, [timeLeft]);

    function handleTimer() {
        setTimeLeft((lastTimeLeft) => {
            return lastTimeLeft ? lastTimeLeft - 1 : 0;
        });
    }

    function isResearchInProcess() {
        return queue && queue.researchId === research.id;
    }

    function getTimeLeft() {
        const dateUTCNow = dayjs.utc(new Date());
        let deadline = dayjs(new Date(queue?.deadline || ""));

        let deadlineString = deadline.format().toString().replace("T", " ");
        let dateArray = deadlineString.split("+");
        const deadlineDate = dateArray[0];

        return dayjs.utc(deadlineDate).unix() - dateUTCNow.unix();
    }

    function isResearchDisabled() {
        return (
            gold > cityResources.gold || population > cityResources.population
        );
    }

    return (
        <div className={"col-sm-6 col-md-4"} key={research.id}>
            <SBuildingImageWrapper
                style={{
                    backgroundImage: `url("../images/researches/${research.id}.svg")`,
                }}
            >
                <SBuildingLvlWrapper>
                    <SBuildingLvl>{lvl}</SBuildingLvl>
                </SBuildingLvlWrapper>
            </SBuildingImageWrapper>
            <h4>{research.title}</h4>
            <span>{research.description}</span>

            {(gold || population) &&
            !isResearchInProcess() &&
            !Boolean(queue && queue.researchId) ? (
                <>
                    <p>
                        Золото: {gold}. Рабочие: {population}
                    </p>

                    <button
                        className={"btn btn-primary"}
                        disabled={isResearchDisabled()}
                        onClick={() => {
                            run(research.id);
                        }}
                    >
                        Исследовать
                    </button>
                </>
            ) : (
                ""
            )}

            {(gold || population) && isResearchInProcess() ? (
                <>
                    <p>Окончание через: {timeLeft} сек.</p>
                    <p>
                        Золото: {gold}. Рабочие: {population}
                    </p>
                    <button
                        className={"btn btn-warning"}
                        onClick={() => {
                            cancel(research.id);
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
