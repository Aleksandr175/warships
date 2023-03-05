import React from "react";
import styled from "styled-components";
import { IBattleLogDetail } from "./Log";

export const LogWarship = ({ data }: { data: IBattleLogDetail }) => {
    return (
        <div>
            <SWarshipIcon
                style={{
                    backgroundImage: `url("../images/warships/simple/${data.warshipId}.svg")`,
                }}
            />
            {data.qty}
        </div>
    );
};

const SWarshipIcon = styled.div`
    display: inline-block;
    background-size: contain;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    margin-right: 10px;

    width: 40px;
    height: 24px;
`;
