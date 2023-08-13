import React from "react";
import {
  ICityResearchQueue,
  ICityResources,
  IResearch,
} from "../../types/types";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { Card } from "../Common/Card";
import styled, { css } from "styled-components";
dayjs.extend(utc);

interface IProps {
  research: IResearch;
  lvl: number;
  gold: number;
  population: number;
  run: (researchId: number) => void;
  cancel: (researchId: number) => void;
  queue: ICityResearchQueue | undefined;
  getResearches: () => void;
  cityResources: ICityResources;
  timeLeft: number;
  selected?: boolean;
}

export const Research = ({ research, lvl, timeLeft, selected }: IProps) => {
  return (
    <SCardWrapper key={research.id} selected={selected}>
      <Card
        object={research}
        qty={lvl}
        imagePath={"researches"}
        timer={timeLeft}
      />
    </SCardWrapper>
  );
};

const SCardWrapper = styled.div<{ selected?: boolean }>`
  border-radius: var(--block-border-radius-small);
  width: 140px;
  height: 80px;
  display: inline-block;
  margin-right: calc(var(--block-gutter-x) / 2);
  margin-bottom: calc(var(--block-gutter-y) / 2);
  overflow: hidden;

  cursor: pointer;

  ${({ selected }) =>
    selected
      ? css`
          border: 2px solid #6f4ca4;
        `
      : ""};
`;
