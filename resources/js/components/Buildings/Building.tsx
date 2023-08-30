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
import { SCardWrapper } from "../styles";
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
