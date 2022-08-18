import React from "react";
import styled from "styled-components";
import { Fleet } from "./Fleet";
import { ICityFleet } from "../types/types";

export const Fleets = ({ fleets }: { fleets: ICityFleet[] }) => {
    return (
        <SColumnFleets className={"col-12"}>
            {fleets.map((fleet) => {
                return <Fleet key={fleet.id} fleet={fleet} />;
            })}
        </SColumnFleets>
    );
};
const SColumnFleets = styled.div`
    margin-bottom: 20px;
    background: white;
`;
