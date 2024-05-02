import React from "react";
import { BattleLogRound } from "./BattleLogRound";

interface IProps {
  userId: number;
  battleLog: IBattleLog;
  battleLogDetails: IBattleLogDetail[];
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

export const MessageBattleLog = ({
  userId,
  battleLog,
  battleLogDetails,
}: IProps) => {
  const getRoundDetails = (round: number) => {
    return battleLogDetails.filter((detail) => detail.round === round);
  };

  const getMaxRound = () => {
    let maxRound = 1;

    battleLogDetails.forEach((logDetail) => {
      if (maxRound < logDetail.round) {
        maxRound = logDetail.round;
      }
    });

    return maxRound;
  };

  const secondUserId = () => {
    return battleLogDetails.find((detail) => detail.userId !== userId)?.userId;
  };

  const renderRounds = () => {
    return [...Array(getMaxRound()).keys()].map((round) => {
      return (
        <BattleLogRound
          roundData={getRoundDetails(round + 1)}
          firstUserId={userId}
          secondUserId={secondUserId() || null}
          round={round + 1}
        />
      );
    });
  };

  return (
    <>
      <div className={"row"}>
        <div className={"col-4"}>Type</div>
        <div className={"col-4"}>Result</div>
      </div>

      <div className={"row"}>
        <div className={"col-4"}>
          {battleLog.attackerUserId === userId ? "Attack" : "Defend"}
        </div>
        <div className={"col-4"}>
          {battleLog.winner === "attacker" ? "Victory" : "Defeat"}
        </div>
      </div>

      <hr />

      {battleLogDetails && renderRounds()}
    </>
  );
};
