import React, { useEffect, useState } from "react";
import { IWarship } from "../../types/types";
import { httpClient } from "../../httpClient/httpClient";

interface IProps {
    dictionary: IWarship[];
    userId: number;
}

interface IBattleLog {
    battleLogId: number;
    attackerUserId: number;
    defenderUserId: number;
    date: string;
}

interface IBattleLogDetail {
    battleLogId: number;
    round: number;
    warshipId: number;
    qty: number;
    destroyed: number;
}

export const Logs = ({ dictionary, userId }: IProps) => {
    const [logs, setLogs] = useState<IBattleLog[]>([]);
    const [logsDetails, setLogsDetails] = useState<IBattleLogDetail[]>([]);

    useEffect(() => {
        httpClient.get("/battle-logs").then((resp) => {
            console.log(resp.data);
            setLogs(resp.data.battleLogs);
            setLogsDetails(resp.data.battleLogsDetails);
        });
    }, []);

    const getNextRoundDetails = () => {};

    // TODO: print logs details!!!
    return (
        <>
            {logs.map((log) => {
                let round = 1;
                let battleLogId = log.battleLogId;

                return (
                    <>
                        <div className={"row"}>
                            <div className={"col-6"}>Date</div>
                            <div className={"col-6"}>Type</div>
                        </div>

                        <div className={"row"}>
                            <div className={"col-6"}>{log.date}</div>
                            <div className={"col-6"}>
                                {log.attackerUserId === userId
                                    ? "Attack"
                                    : "Defend"}
                            </div>
                        </div>

                        <p>Log Info</p>

                        {getNextRoundDetails(battleLogId, round).map(() => {
                            <>
                                <div className={"row"}>
                                    <div className={"col-6"}>Warship</div>
                                    <div className={"col-6"}>Destroyed</div>
                                </div>
                            </>;
                        })}
                    </>
                );
            })}
        </>
    );
};
