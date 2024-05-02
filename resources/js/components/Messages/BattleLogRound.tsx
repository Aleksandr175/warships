import React from "react";
import { IBattleLogDetail } from "./MessageBattleLog";
import { BattleLogWarship } from "./BattleLogWarship";
import styled from "styled-components";
import { Icon } from "../Common/Icon";

interface IProps {
  roundData: IBattleLogDetail[];
  firstUserId: number | null;
  secondUserId: number | null;
  round: number;
}

export const BattleLogRound = ({
  roundData,
  firstUserId,
  secondUserId,
  round,
}: IProps) => {
  const getWarshipLog = (userId: number | null, warshipId: number) => {
    return roundData.find(
      (data) => data.userId === userId && data.warshipId === warshipId
    );
  };

  const renderLogWarship = (userId: number | null, warshipId: number) => {
    const warshipLogData = getWarshipLog(userId, warshipId);

    if (warshipLogData) {
      return <BattleLogWarship data={warshipLogData} />;
    }

    return null;
  };

  const renderLostWarships = (userId: number | null) => {
    const detailsOfDestroyedWarships = roundData.filter(
      (logDetail) => logDetail.userId === userId && logDetail.destroyed
    );

    if (!detailsOfDestroyedWarships.length) {
      return <span>Nothing</span>;
    }

    return detailsOfDestroyedWarships.map((detail) => {
      return (
        <span>
          <SWarshipIcon
            style={{
              backgroundImage: `url("../images/warships/simple/light/${detail.warshipId}.svg")`,
            }}
          />
          {detail.destroyed}
        </span>
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
                  {renderLogWarship(firstUserId, 1)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(firstUserId, 2)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(firstUserId, 3)}
                </div>
              </SFirstRow>
              <div className={"row"}>
                <div className={"col-6"}>
                  {renderLogWarship(firstUserId, 4)}
                </div>
                <div className={"col-6"}>
                  {renderLogWarship(firstUserId, 5)}
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
                  {renderLogWarship(secondUserId, 1)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(secondUserId, 2)}
                </div>
                <div className={"col-4"}>
                  {renderLogWarship(secondUserId, 3)}
                </div>
              </div>
              <div className={"row"}>
                <div className={"col-6"}>
                  {renderLogWarship(secondUserId, 4)}
                </div>
                <div className={"col-6"}>
                  {renderLogWarship(secondUserId, 5)}
                </div>
              </div>
            </div>
          </div>
        </div>
      </SRoundBg>

      <br />
      <p>
        <b>Round result:</b>
      </p>
      <p>First player lost: {renderLostWarships(firstUserId)}</p>
      <p>Second player lost: {renderLostWarships(secondUserId)}</p>

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
