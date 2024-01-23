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
  display: inline-flex;
  position: relative;
  vertical-align: middle;
  align-items: center;
  justify-content: center;

  ${({ size }) =>
    size === "small"
      ? css`
          width: 16px;
          height: 16px;
        `
      : ""};
`;

const SIcon = styled.i<{ size?: TSize }>`
  width: 30px;
  height: 30px;
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
    size === "normal"
      ? css`
          width: 24px;
          height: 24px;
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
