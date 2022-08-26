import React from "react";
import styled from "styled-components";
import { Fleet } from "./Fleet";
import {
    ICityFleet,
    IDictionary,
    IFleetDetail,
    IMapCity,
} from "../types/types";

export const Fleets = ({
    fleets,
    dictionaries,
    fleetCities,
    fleetDetails,
}: {
    fleets: ICityFleet[];
    dictionaries: IDictionary;
    fleetCities: IMapCity[];
    fleetDetails: IFleetDetail[];
}) => {
    const getFleetDetails = (fleetId: number): IFleetDetail[] => {
        return fleetDetails.filter((detail) => detail.fleetId === fleetId);
    };

    return (
        <SColumnFleets className={"col-12"}>
            {fleets.map((fleet) => {
                return (
                    <Fleet
                        key={fleet.id}
                        fleet={fleet}
                        fleetDetails={getFleetDetails(fleet.id)}
                        dictionaries={dictionaries}
                        fleetCities={fleetCities}
                    />
                );
            })}
        </SColumnFleets>
    );
};
const SColumnFleets = styled.div`
    margin-bottom: 20px;
    background: white;
`;
