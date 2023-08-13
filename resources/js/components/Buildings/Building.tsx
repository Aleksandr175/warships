import React from "react";
import {
  IBuilding,
  IBuildingsProduction,
  ICityBuildingQueue,
  ICityResources,
} from "../../types/types";
import styled, { css } from "styled-components";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { Card } from "../Common/Card";
dayjs.extend(utc);

interface IProps {
  building: IBuilding;
  lvl: number;
  gold: number;
  population: number;
  run: (buildingId: number) => void;
  cancel: (buildingId: number) => void;
  queue: ICityBuildingQueue | undefined;
  getBuildings: () => void;
  cityResources: ICityResources;
  buildingsProduction: IBuildingsProduction[];
  timeLeft: number;
  selected?: boolean;
}

export const Building = ({ building, lvl, timeLeft, selected }: IProps) => {
  return (
    <SCardWrapper key={building.id} selected={selected}>
      <Card
        object={building}
        qty={lvl}
        imagePath={"buildings"}
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
