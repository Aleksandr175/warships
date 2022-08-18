import React, { useEffect, useRef, useState } from "react";
import { ICityFleet } from "../types/types";
import dayjs from "dayjs";

export const Fleet = ({ fleet }: { fleet: ICityFleet }) => {
    const [timeLeft, setTimeLeft] = useState<number | null>(null);
    const timer = useRef();

    useEffect(() => {
        setTimeLeft(getTimeLeft());

        // @ts-ignore
        timer.current = setInterval(handleTimer, 1000);

        return () => {
            clearInterval(timer.current);
        };
    }, [fleet]);

    function handleTimer() {
        setTimeLeft((lastTimeLeft) => {
            return lastTimeLeft ? lastTimeLeft - 1 : 0;
        });
    }

    useEffect(() => {
        if (timeLeft === -1) {
            setTimeLeft(0);
        }
    }, [timeLeft]);

    function getTimeLeft() {
        const dateUTCNow = dayjs.utc(new Date());
        let deadline = dayjs(new Date(fleet?.deadline || ""));

        let deadlineString = deadline.format().toString().replace("T", " ");
        let dateArray = deadlineString.split("+");
        const deadlineDate = dateArray[0];

        return dayjs.utc(deadlineDate).unix() - dateUTCNow.unix();
    }

    return (
        <div>
            Fleet: cityId: {fleet.cityId}, target cityID: {fleet.targetCityId},
            task: {fleet.fleetTaskId}
            Deadline: {fleet.deadline}
            Ending in: {timeLeft} sec.
        </div>
    );
};
