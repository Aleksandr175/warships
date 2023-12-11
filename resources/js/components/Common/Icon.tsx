import React from "react";
import styled, { css } from "styled-components";

type TSize = "small" | "big" | "normal" | "extra-big";

export const Icon = ({
  title,
  size = "normal",
}: {
  title: string;
  size?: TSize;
}) => {
  return (
    <SIconWrapper size={size}>
      <SIcon
        size={size}
        style={{
          backgroundImage: `url("../images/icons/${title}.svg")`,
        }}
      />
    </SIconWrapper>
  );
};

const SIconWrapper = styled.i<{ size?: TSize }>`
  width: 30px;
  height: 30px;
  display: inline-block;
  position: relative;
  vertical-align: middle;

  ${({ size }) =>
    size === "small"
      ? css`
          width: 16px;
          height: 16px;
        `
      : ""};
`;

const SIcon = styled.i<{ size?: TSize }>`
  position: absolute;
  width: 30px;
  height: 30px;
  left: 0;
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center center;

  ${({ size }) =>
    size === "small"
      ? css`
          width: 16px;
          height: 16px;
        `
      : ""};

  ${({ size }) =>
    size === "extra-big"
      ? css`
          width: 48px;
          height: 48px;
        `
      : ""};
`;
