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
            <SItemImageWrapper
                style={{
                    backgroundImage: `url("../images/${imagePath}/${object.id}.svg")`,
                }}
            >
                <SItemCornerWrapper>
                    <SItemCorner>{qty}</SItemCorner>
                </SItemCornerWrapper>
            </SItemImageWrapper>
            <h4>{object.title}</h4>
            <span>{object.description}</span>
        </>
    );
};

import styled from "styled-components";

const SItemImageWrapper = styled.div`
    border: 1px solid black;
    height: 100px;
    margin-bottom: 20px;
    position: relative;

    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #ddd;
`;

const SItemCornerWrapper = styled.div`
    position: absolute;
    top: 0;
    right: 0;
    border: 30px solid transparent;
    border-top: 30px solid #ccc;
    border-right: 30px solid #ccc;
`;

const SItemCorner = styled.span`
    position: absolute;
    top: -25px;
    right: -20px;
    font-size: 16px;
    font-weight: 700;
`;
