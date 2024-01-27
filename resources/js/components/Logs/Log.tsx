import React, { useEffect, useState } from "react";
import { httpClient } from "../../httpClient/httpClient";
import { NavLink, useParams } from "react-router-dom";
import { Round } from "./Round";
import { SContent } from "../styles";

interface IProps {
  userId: number;
}

export interface IBattleLog {
  battleLogId: number;
  attackerUserId: number;
  defenderUserId: number;
  winner: "attacker" | "defender";
  date: string;
}

export interface IBattleLogDetail {
  battleLogId: number;
  round: number;
  warshipId: number;
  qty: number;
  destroyed: number;
  userId: number | null;
}

export const Log = ({ userId }: IProps) => {
  const [log, setLog] = useState<IBattleLog>();
  const [logDetails, setLogDetails] = useState<IBattleLogDetail[]>([]);
  const [isLoading, setIsLoading] = useState(false);

  let params = useParams<{ id: string }>();

  useEffect(() => {
    getLog(Number(params.id));
  }, [location]);

  const getLog = (battleLogId: number) => {
    setIsLoading(true);

    httpClient.get("/battle-logs/" + battleLogId).then((resp) => {
      setLog(resp.data.battleLog);
      setLogDetails(resp.data.battleLogDetails);
      setIsLoading(false);
    });
  };

  console.log(logDetails);

  const getRoundDetails = (round: number) => {
    return logDetails.filter((detail) => detail.round === round);
  };

  const getMaxRound = () => {
    let maxRound = 1;

    logDetails.forEach((logDetail) => {
      if (maxRound < logDetail.round) {
        maxRound = logDetail.round;
      }
    });

    return maxRound;
  };

  const secondUserId = () => {
    return logDetails.find((detail) => detail.userId !== userId)?.userId;
  };

  const renderRounds = () => {
    return [...Array(getMaxRound()).keys()].map((round) => {
      return (
        <Round
          roundData={getRoundDetails(round + 1)}
          firstUserId={userId}
          secondUserId={secondUserId() || null}
          round={round + 1}
        />
      );
    });
  };

  return (
    <SContent>
      <NavLink to={"/logs"}>Back to Logs</NavLink>

      {log && (
        <>
          <div className={"row"}>
            <div className={"col-4"}>Date</div>
            <div className={"col-4"}>Type</div>
            <div className={"col-4"}>Result</div>
          </div>

          <div className={"row"}>
            <div className={"col-4"}>{log.date}</div>
            <div className={"col-4"}>
              {log.attackerUserId === userId ? "Attack" : "Defend"}
            </div>
            <div className={"col-4"}>
              {log.winner === "attacker" ? "Victory" : "Defeat"}
            </div>
          </div>

          <hr />

          {logDetails && renderRounds()}
        </>
      )}
    </SContent>
  );
};
