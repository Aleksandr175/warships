import React from "react";
import styled from "styled-components";
import { convertSecondsToTime } from "../../utils";
import { SBadge } from "./styles";

interface IProps {
  objectId: number;
  labelText?: string | number;
  imagePath: string;
  timer: number;
}

export const Card = ({ objectId, labelText, imagePath, timer = 0 }: IProps) => {
  return (
    <SCardImageWrapper
      style={{
        backgroundImage: `url("../images/${imagePath}/${objectId}.svg")`,
      }}
    >
      <SLabelWrapper>
        {timer > 0 && <STimer>{convertSecondsToTime(timer)}</STimer>}{" "}
        {labelText}
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

const SLabelWrapper = styled(SBadge)`
  position: absolute;
  bottom: 5px;
  right: 5px;
  border-radius: 24px;
  min-width: 24px;
  align-items: center;
  justify-content: center;
`;

const STimer = styled.span`
  display: inline-block;
  padding-right: 5px;
  font-size: 10px;
`;
