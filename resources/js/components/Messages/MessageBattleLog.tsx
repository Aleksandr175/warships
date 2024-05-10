import React from "react";
import { BattleLogRound } from "./BattleLogRound";
import { IMessage } from "./types";
import { ICityShort } from "../../types/types";
import { Icon } from "../Common/Icon";
import styled from "styled-components";
import { SBadge } from "../Common/styles";

interface IProps {
  userId: number;
  message: IMessage;
  cities: ICityShort[];
  battleLog: IBattleLog;
  battleLogDetails: IBattleLogDetail[];
}

export interface IBattleLog {
  battleLogId: number;
  attackerUserId: number;
  defenderUserId: number;
  winner: "attacker" | "defender";
  date: string;
  fortressPercent: number;
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
  message,
  cities,
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
          attackerCity={attackerCity}
          defenderCity={defenderCity}
          round={round + 1}
        />
      );
    });
  };

  const getCity = (cityId: number) =>
    cities?.find((city) => city.id === cityId);

  const attackerCity = getCity(message.cityId || 0);
  const defenderCity = getCity(message.targetCityId || 0);

  const hasFortress = !!battleLog.fortressPercent;
  const fortressPercent = battleLog.fortressPercent;

  return (
    <>
      <SAttackCities>
        {attackerCity?.title} (
        {battleLog.winner === "attacker" ? "Win" : "Lost"}){" "}
        <Icon title={"attack"} /> {defenderCity?.title} (
        {battleLog.winner === "attacker" ? "Lost" : "Win"})
      </SAttackCities>

      <hr />
      {hasFortress && (
        <p>
          Defender had Fortress, which increased attack of defender fleet by{" "}
          <SBadge>{fortressPercent}%</SBadge>
        </p>
      )}

      {battleLogDetails && battleLogDetails.length > 0 && renderRounds()}
    </>
  );
};

const SAttackCities = styled.div`
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
`;
