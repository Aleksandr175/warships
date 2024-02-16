import styled, { css } from "styled-components";
import React from "react";

export const ProgressBar = ({ percent }: { percent: number }) => {
  console.log("Progress ", percent);

  return (
    <SProgressBarWrapper>
      <SProgressBarLine>
        <SProgressBarFilledLine percent={percent}></SProgressBarFilledLine>
      </SProgressBarLine>
      <SProgressBarValueWrapper>
        <SprogressBarValue percent={percent}>{percent}</SprogressBarValue>
      </SProgressBarValueWrapper>
    </SProgressBarWrapper>
  );
};

const SProgressBarWrapper = styled.div`
  position: relative;
`;

const SProgressBarLine = styled.div`
  height: 10px;
  background: #d9d9d9;
  border-radius: var(--block-border-radius);
  overflow: hidden;
  position: relative;
`;

const SProgressBarFilledLine = styled.div<{ percent?: number }>`
    position: absolute;
    top: 0;
    left: 0;
    border-radius: var(--block-border-radius);
    content: "";
    background: #6f4ca4;
    height: 100%;

    ${({ percent }) =>
      percent &&
      css`
        width: ${percent}%;
      `};
  }
`;

const SprogressBarValue = styled.div<{ percent?: number }>`
  width: 18px;
  height: 18px;
  margin-top: -4px;
  margin-left: -9px;

  background: #6f4ca4;
  border-radius: 50%;

  position: absolute;
  top: 0;

  color: white;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;

  ${({ percent }) =>
    percent &&
    css`
      left: ${percent}%;
    `};
`;

const SProgressBarValueWrapper = styled.div`
  position: absolute;
  left: 7px;
  right: 7px;
  top: 0;
`;
