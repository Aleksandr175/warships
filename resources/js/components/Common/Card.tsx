import React from "react";
import styled from "styled-components";
import { convertSecondsToTime } from "../../utils";

interface IProps {
    object: {
        id: number;
        title: string;
        description: string;
    };
    qty?: number;
    imagePath: string;
    timer: number;
}

export const Card = ({ object, qty, imagePath, timer }: IProps) => {
    return (
        <SCardImageWrapper
            style={{
                backgroundImage: `url("../images/${imagePath}/${object.id}.svg")`,
            }}
        >
            <SLabelWrapper>
                {timer > 0 && <STimer>{convertSecondsToTime(timer)}</STimer>}{" "}
                {qty}
            </SLabelWrapper>
        </SCardImageWrapper>
    );
};

const SCardImageWrapper = styled.div`
    position: relative;

    display: flex;
    height: 100%;

    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #ddd;
`;

const SLabelWrapper = styled.div`
    position: absolute;
    bottom: 5px;
    right: 5px;
    height: 24px;
    padding-left: 5px;
    padding-right: 5px;
    min-width: 24px;
    width: auto;
    border-radius: 24px;
    background: #6f4ca4;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
`;

const STimer = styled.span`
    display: inline-block;
    padding-right: 5px;
    font-size: 10px;
`;
