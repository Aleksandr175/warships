import styled from "styled-components";

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
