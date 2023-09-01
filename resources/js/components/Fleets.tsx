import React from "react";
import styled from "styled-components";
import { Fleet } from "./Fleet";
import {
  ICityFleet,
  IDictionary,
  IFleetDetail,
  IFleetIncoming,
  IMapCity,
} from "../types/types";

export const Fleets = ({
  fleets,
  fleetsIncoming,
  dictionaries,
  fleetCitiesDictionary,
  fleetDetails,
}: {
  fleets: ICityFleet[];
  fleetsIncoming: IFleetIncoming[] | undefined;
  dictionaries: IDictionary;
  fleetCitiesDictionary: IMapCity[];
  fleetDetails: IFleetDetail[];
}) => {
  const getFleetDetails = (fleetId: number): IFleetDetail[] => {
    return fleetDetails.filter((detail) => detail.fleetId === fleetId);
  };

  return (
    <SColumnFleets className={"col-12"}>
      {fleets &&
        fleets.map((fleet) => {
          return (
            <Fleet
              key={fleet.id}
              fleet={fleet}
              fleetDetails={getFleetDetails(fleet.id)}
              dictionaries={dictionaries}
              // TODO: sent city and target city, not whole dictionary of cities
              fleetCities={fleetCitiesDictionary}
            />
          );
        })}

      {fleetsIncoming && fleetsIncoming.length > 0 && (
        <>
          <p>Incoming Fleets</p>
          {fleetsIncoming.map((fleet) => {
            return (
              <Fleet
                key={fleet.id}
                fleet={fleet}
                fleetDetails={getFleetDetails(fleet.id)}
                dictionaries={dictionaries}
                // TODO: sent city and target city, not whole dictionary of cities
                fleetCities={fleetCitiesDictionary}
              />
            );
          })}
        </>
      )}
    </SColumnFleets>
  );
};

const SColumnFleets = styled.div``;
