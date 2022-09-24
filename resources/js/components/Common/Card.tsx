import React from "react";

interface IProps {
    object: {
        id: number;
        title: string;
        description: string;
    };
    qty?: number;
    imagePath: string;
}

export const Card = ({ object, qty, imagePath }: IProps) => {
    return (
        <>
            <SCardImageWrapper
                style={{
                    backgroundImage: `url("../images/${imagePath}/${object.id}.svg")`,
                }}
            >
                <SCardCornerWrapper>
                    <SCardCorner>{qty}</SCardCorner>
                </SCardCornerWrapper>
            </SCardImageWrapper>
            <SCardName>{object.title}</SCardName>
            <span>{object.description}</span>
        </>
    );
};

import styled from "styled-components";

const SCardImageWrapper = styled.div`
    border: 1px solid black;
    height: 80px;
    margin-bottom: 20px;
    position: relative;

    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #ddd;
`;

const SCardCornerWrapper = styled.div`
    position: absolute;
    top: 0;
    right: 0;
    border: 30px solid transparent;
    border-top: 30px solid #ccc;
    border-right: 30px solid #ccc;
`;

const SCardCorner = styled.span`
    position: absolute;
    top: -25px;
    right: -20px;
    font-size: 16px;
    font-weight: 700;
`;

const SCardName = styled.h2`
    font-size: 20px;
    font-weight: 700;
`;
