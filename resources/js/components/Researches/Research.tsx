import React from "react";
import { IResearch } from "../../types/types";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import { Card } from "../Common/Card";
import { SCardWrapper } from "../styles";
dayjs.extend(utc);

interface IProps {
  research: IResearch;
  lvl: number;
  timeLeft: number;
  selected?: boolean;
}

export const Research = ({ research, lvl, timeLeft, selected }: IProps) => {
  return (
    <SCardWrapper key={research.id} selected={selected}>
      <Card
        objectId={research.id}
        labelText={lvl}
        imagePath={"researches"}
        timer={timeLeft}
      />
    </SCardWrapper>
  );
};
