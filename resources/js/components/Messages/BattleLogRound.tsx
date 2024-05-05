import React from "react";
import { IBattleLogDetail } from "./MessageBattleLog";
import { BattleLogWarship } from "./BattleLogWarship";
import styled from "styled-components";
import { Icon } from "../Common/Icon";
import { ICityShort } from "../../types/types";

interface IProps {
  roundData: IBattleLogDetail[];
  attackerCity: ICityShort | undefined;
  defenderCity: ICityShort | undefined;
  round: number;
}

export const BattleLogRound = ({
  roundData,
  attackerCity,
  defenderCity,
  round,
}: IProps) => {
  const getWarshipLog = (
    userId: number | null | undefined,
    warshipId: number
  ): IBattleLogDetail | undefined => {
    return roundData.find(
      (data) => data.userId === userId && data.warshipId === warshipId
    );
  };

  const renderLogWarship = (
    userId: number | null | undefined,
    warshipId: number
  ) => {
    const warshipLogData = getWarshipLog(userId, warshipId);

    if (warshipLogData) {
      return (
        <BattleLogWarship
          data={{
            warshipId: warshipLogData.warshipId,
            qty: warshipLogData.qty,
          }}
        />
      );
    }

    return null;
  };

  const renderLostWarships = (userId: number | null | undefined) => {
    const detailsOfDestroyedWarships = roundData.filter(
      (logDetail) => logDetail.userId === userId && logDetail.destroyed
    );

    if (!detailsOfDestroyedWarships.length) {
      return <span>Nothing</span>;
    }

    return detailsOfDestroyedWarships.map((detail) => {
      return (
        <SLostWarship>
          <BattleLogWarship
            mode={"dark"}
            data={{
              warshipId: detail.warshipId,
              qty: detail.destroyed,
            }}
          />
        </SLostWarship>
      );
    });
  };

  return (
    <SRoundWrapper>
      <SRound>Round: {round}</SRound>
      <SRoundBg>
        <div>
          <div className={"row"}>
            <div className={"offset-1 col-10 text-center"}>
              <SFirstRow className={"row"}>
                <div className={"col-4"}>
                  {renderLogWarship(attackerCity?.userId, 1)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(attackerCity?.userId, 2)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(attackerCity?.userId, 3)}
                </div>
              </SFirstRow>
              <div className={"row"}>
                <div className={"col-6"}>
                  {renderLogWarship(attackerCity?.userId, 4)}
                </div>
                <div className={"col-6"}>
                  {renderLogWarship(attackerCity?.userId, 5)}
                </div>
              </div>
            </div>
          </div>
          {/* TODO: improve logic for icons positions */}
          <SFireIcons>
            <SIconWrapper>
              <Icon size={"extra-big"} title={"bullets"} />
            </SIconWrapper>
            <SIconWrapper>
              <Icon size={"extra-big"} title={"bullets"} />
            </SIconWrapper>
            <SIconWrapper>
              <Icon size={"extra-big"} title={"bullets"} />
            </SIconWrapper>
          </SFireIcons>
          <div className={"row"}>
            <div className={"offset-1 col-10 text-center"}>
              <div className={"row"}>
                <div className={"col-4"}>
                  {renderLogWarship(defenderCity?.userId, 1)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(defenderCity?.userId, 2)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(defenderCity?.userId, 3)}
                </div>
              </div>
              <div className={"row"}>
                <div className={"col-6"}>
                  {renderLogWarship(defenderCity?.userId, 4)}
                </div>
                <div className={"col-6"}>
                  {renderLogWarship(defenderCity?.userId, 5)}
                </div>
              </div>
            </div>
          </div>
        </div>
      </SRoundBg>

      <br />
      <p>
        <b>Round Result:</b>
      </p>
      <p>Attacker Lost: {renderLostWarships(attackerCity?.userId)}</p>
      <p>Defender Lost: {renderLostWarships(defenderCity?.userId)}</p>

      <br />
      <hr />
    </SRoundWrapper>
  );
};
const SWarshipIcon = styled.div`
  display: inline-block;
  background-size: contain;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  margin-right: 10px;

  width: 40px;
  height: 24px;
`;

const SRoundWrapper = styled.div`
  margin-bottom: 40px;
`;

const SRound = styled.div`
  font-weight: 700;
  margin-bottom: 20px;
`;

const SRoundBg = styled.div`
  background: url("/images/logs/bg.svg") 50% 50% no-repeat;
  background-size: cover;
  border-radius: 8px;

  max-width: 500px;
  margin: 30px auto 0;
  height: 300px;

  font-weight: 700;

  display: flex;
  align-items: center;

  > div {
    width: 100%;
  }
`;

const SFirstRow = styled.div`
  margin-top: 30px;
`;

const SIconWrapper = styled.div`
  display: inline-block;
`;

const SFireIcons = styled.div`
  height: 60px;
  margin: 0 auto;
  width: 300px;
  position: relative;
  display: flex;
  justify-content: space-between;
`;

const SLostWarship = styled.span`
  display: inline-block;
  padding-right: 10px;
`;
