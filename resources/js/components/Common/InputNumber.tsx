import React, { useState } from "react";
import styled from "styled-components";

interface IProps {
    onChange: (value: number) => void;
    value: number;
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
        <SInput
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

                number = parseInt(String(number), 10);

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

const SInput = styled.input`
    display: inline-block;
    width: 100%;
    padding: 5px;

    &:disabled {
        background: #eee;
        cursor: not-allowed;
    }
`;
