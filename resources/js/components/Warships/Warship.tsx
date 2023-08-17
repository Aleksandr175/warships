import React from "react";
import { IWarship } from "../../types/types";
import styled, { css } from "styled-components";
import { Card } from "../Common/Card";

interface IProps {
  selected?: boolean;
  warship: IWarship;
  currentQty?: number;
}

export const Warship = ({ warship, selected, currentQty }: IProps) => {
  return (
    <SCardWrapper key={warship.id} selected={selected}>
      <Card
        object={warship}
        qty={currentQty}
        timer={0}
        imagePath={"warships"}
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
