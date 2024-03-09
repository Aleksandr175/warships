import React from "react";
import { IWarship } from "../../types/types";
import styled, { css } from "styled-components";
import { Card } from "../Common/Card";
import { SCardWrapper } from "../styles";

interface IProps {
  selected?: boolean;
  warship: IWarship;
  currentQty?: number;
}

export const Warship = ({ warship, selected, currentQty }: IProps) => {
  return (
    <SCardWrapper key={warship.id} selected={selected}>
      <Card
        objectId={warship.id}
        labelText={currentQty}
        timer={0}
        imagePath={"warships"}
      />
    </SCardWrapper>
  );
};
