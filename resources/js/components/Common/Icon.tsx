import React from "react";
import styled from "styled-components";

export const Icon = ({ title }: { title: string }) => {
    return (
        <SIconWrapper>
            <SIcon
                style={{
                    backgroundImage: `url("../images/icons/${title}.svg")`,
                }}
            />
        </SIconWrapper>
    );
};

const SIconWrapper = styled.i`
    width: 30px;
    height: 30px;
    display: inline-block;
    position: relative;
    vertical-align: middle;
`;

const SIcon = styled.i`
    position: absolute;
    width: 30px;
    height: 30px;
    left: 0;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center center;
`;
