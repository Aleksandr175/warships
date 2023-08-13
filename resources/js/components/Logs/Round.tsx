import React from "react";
import { IWarship } from "../../types/types";
import { IBattleLog, IBattleLogDetail } from "./Log";
import { LogWarship } from "./LogWarship";
import styled from "styled-components";
import { Icon } from "../Common/Icon";

interface IProps {
    dictionary: IWarship[];
    roundData: IBattleLogDetail[];
    firstUserId: number | null;
    secondUserId: number | null;
    round: number;
    log: IBattleLog;
}

export const Round = ({
    dictionary,
    roundData,
    firstUserId,
    secondUserId,
    round,
    log,
}: IProps) => {
    const getWarshipLog = (userId: number | null, warshipId: number) => {
        return roundData.find(
            (data) => data.userId === userId && data.warshipId === warshipId
        );
    };

    const renderLogWarship = (userId: number | null, warshipId: number) => {
        const warshipLogData = getWarshipLog(userId, warshipId);

        if (warshipLogData) {
            return <LogWarship data={warshipLogData} />;
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
                            backgroundImage: `url("../images/warships/simple/${detail.warshipId}.svg")`,
                        }}
                    />
                    {detail.destroyed}
                </span>
            );
        });
    };

    return (
        <SRoundWrapper>
            <div>
                <b>Round: {round}</b>
            </div>
            <div className={"row"}>
                <div className={"offset-3 col-6 text-center"}>
                    <div className={"row"}>
                        <div className={"col-4"}>
                            {renderLogWarship(firstUserId, 1)}
                        </div>
                        <div className={"col-4"}>
                            {renderLogWarship(firstUserId, 2)}
                        </div>
                        <div className={"col-4"}>
                            {renderLogWarship(firstUserId, 3)}
                        </div>
                    </div>
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

            {/* TODO: temporary */}
            <div
                className={"text-center"}
                style={{ paddingTop: "15px", paddingBottom: "15px" }}
            >
                <Icon title={"attack"} />
            </div>

            <div className={"row"}>
                <div className={"offset-3 col-6 text-center"}>
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