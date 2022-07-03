import React, { useEffect, useRef, useState } from "react";
import {
    ICityResearchQueue,
    ICityResources,
    IResearch,
} from "../../types/types";
import styled from "styled-components";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { Card } from "../Common/Card";
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
            <Card object={research} qty={lvl} imagePath={"researches"} />

            {(gold || population) &&
            !isResearchInProcess() &&
            !Boolean(queue && queue.researchId) ? (
                <>
                    <p>
                        Gold: {gold}. Workers: {population}
                    </p>

                    <button
                        className={"btn btn-primary"}
                        disabled={isResearchDisabled()}
                        onClick={() => {
                            run(research.id);
                        }}
                    >
                        Research
                    </button>
                </>
            ) : (
                ""
            )}

            {(gold || population) && isResearchInProcess() ? (
                <>
                    <p>Ending in: {timeLeft} sec.</p>
                    <p>
                        Gold: {gold}. Workers: {population}
                    </p>
                    <button
                        className={"btn btn-warning"}
                        onClick={() => {
                            cancel(research.id);
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
