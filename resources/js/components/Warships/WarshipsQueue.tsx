import React, { useEffect, useRef, useState } from "react";
import {
    ICityWarshipQueue,
    IWarship,
} from "../../types/types";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
    dictionary: IWarship[];
    queue?: ICityWarshipQueue[];
    sync: () => void;
}

export const WarshipsQueue = ({ dictionary, queue, sync }: IProps) => {
    const timer = useRef();
    const [tempQueue, setTempQueue] = useState(queue || []);

    useEffect(() => {
        setTempQueue(queue || []);

        // @ts-ignore
        timer.current = setInterval(handleTimer, 1000);

        return () => {
            clearInterval(timer.current);
        };
    }, [queue]);

    function handleTimer() {
        const q: ICityWarshipQueue[] = tempQueue;

        const newQ = q?.map((item) => {
            if (item.time > 0) {
                item.time -= 1;
            }

            if (item.time === 0) {
                sync();
            }

            return item;
        });

        setTempQueue(newQ);
    }

    function getTimeLeft(deadlineStr: string): number {
        const dateUTCNow = dayjs.utc(new Date());
        let deadline = dayjs(new Date(deadlineStr));

        let deadlineString = deadline.format().toString().replace("T", " ");
        let dateArray = deadlineString.split("+");
        const deadlineDate = dateArray[0];

        return dayjs.utc(deadlineDate).unix() - dateUTCNow.unix();
    }

    function getWarshipName(warshipId: number): string | undefined {
        return dictionary.find((warship) => warship.id === warshipId)?.title;
    }

    return (
        <table>
            <thead>
                <tr>
                    <th>Warship</th>
                    <th>Qty</th>
                    <th>Time Left</th>
                    <th>Deadline</th>
                </tr>
            </thead>

            <tbody>
                {queue?.map((item) => {
                    return (
                        <tr>
                            <td>{getWarshipName(item.warshipId)}</td>
                            <td>{item.qty}</td>
                            <td>{getTimeLeft(item.deadline)}</td>
                            <td>{item.deadline}</td>
                        </tr>
                    );
                })}
            </tbody>
        </table>
    );
};
