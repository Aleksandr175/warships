import React, { useState } from "react";
import styled from "styled-components";

interface IProps {
  onChange: (value: number) => void;
  value: number | string | null;
  maxNumber?: number;
  disabled?: boolean;
}

export const InputNumber: React.FC<IProps> = ({
  onChange,
  value,
  maxNumber,
  disabled,
  ...rest
}) => {
  return (
    <SInputNumber
      {...rest}
      disabled={disabled}
      type="text"
      value={value || ""}
      onKeyPress={(event) => {
        if (!/[0-9]/.test(event.key)) {
          event.preventDefault();
        }
      }}
      onChange={(e) => {
        let number: string | number = e.currentTarget.value;

        number = Number(number);

        if (!number || number < 0) {
          number = 0;
        }

        if (number > 0) {
          if (maxNumber !== undefined && number > maxNumber) {
            number = maxNumber;
          }
        }

        onChange(number);
      }}
    />
  );
};

export const SInputNumber = styled.input`
  display: inline-block;
  width: 100%;
  padding: 5px;
  border: none;
  border-radius: 5px;

  &:disabled {
    background: #eee;
    cursor: not-allowed;
  }
`;
