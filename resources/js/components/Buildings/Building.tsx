import React from "react";
import { IBuilding } from "../../types/types";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { Card } from "../Common/Card";
import { SCardWrapper } from "../styles";
dayjs.extend(utc);

interface IProps {
  building: IBuilding;
  lvl: number;
  timeLeft: number;
  selected?: boolean;
}

export const Building = ({ building, lvl, timeLeft, selected }: IProps) => {
  return (
    <SCardWrapper key={building.id} selected={selected}>
      <Card
        objectId={building.id}
        labelText={lvl}
        imagePath={"buildings"}
        timer={timeLeft}
      />
    </SCardWrapper>
  );
};
