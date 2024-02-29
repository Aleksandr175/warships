import styled, { css } from "styled-components";

export const SH1 = styled.h1`
  font-size: 14px;
  font-weight: 700;
`;

export const SH2 = styled.h2`
  font-size: 14px;
  font-weight: 700;
`;

export const SText = styled.p`
  color: var(--text-color);
  margin-bottom: 5px;
`;

export const SItemImageWrapper = styled.div`
  border: 1px solid black;
  height: 100px;
  margin-bottom: 20px;
  position: relative;

  background-position: 50% 50%;
  background-repeat: no-repeat;
  background-size: cover;
  background-color: #ddd;
`;

export const SItemCornerWrapper = styled.div`
  position: absolute;
  top: 0;
  right: 0;
  border: 30px solid transparent;
  border-top: 30px solid #ccc;
  border-right: 30px solid #ccc;
`;

export const SItemCorner = styled.span`
  position: absolute;
  top: -25px;
  right: -20px;
  font-size: 16px;
  font-weight: 700;
`;

// Main Layout

export const SAppContainer = styled.div``;

export const SColumn = styled.div`
  background: var(--background-color);
  padding: var(--block-padding);
  border-radius: var(--block-border-radius);
  margin-bottom: var(--block-gutter-y);
`;

export const SContent = styled.div`
  background: var(--background-color);
  border-radius: var(--block-border-radius);
  padding: var(--block-padding);
  margin-bottom: var(--block-gutter-y);
`;

export const SButtonsBlock = styled.div`
  display: flex;
  align-items: center;
  margin-bottom: 20px;
`;

export const SParams = styled.div`
  display: flex;
  margin-bottom: 20px;
  flex-wrap: wrap;
`;

export const SParam = styled.div`
  width: 100px;
  color: #949494;
  display: flex;
  align-items: center;

  > i {
    margin-right: 5px;
  }
`;

export const SCardWrapper = styled.div<{ selected?: boolean }>`
  border-radius: var(--block-border-radius-small);
  width: 132px;
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

export const SPopoverWrapper = styled.div`
  background: white;
  padding: 10px;
  border-radius: 5px;
  position: relative;
  min-width: 150px;
`;

export const SCloseButton = styled.div`
  position: absolute;
  right: 0;
  top: 0;
`;

export const SPopoverHeader = styled.div`
  font-weight: 700;
  padding-right: 20px;
  padding-bottom: 10px;
`;

export const SPopoverButtons = styled.div`
  display: flex;
  gap: 10px;
`;
