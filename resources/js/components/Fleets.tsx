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
  fleets: ICityFleet[] | undefined;
  fleetsIncoming: IFleetIncoming[] | undefined;
  dictionaries: IDictionary;
  fleetCitiesDictionary: IMapCity[];
  fleetDetails: IFleetDetail[] | undefined;
}) => {
  const getFleetDetails = (fleetId: number): IFleetDetail[] => {
    return fleetDetails?.filter((detail) => detail.fleetId === fleetId)!;
  };

  const tradeFleetTaskId = dictionaries?.fleetTasksDictionary?.find(
    (task) => task.slug === "trade"
  )?.id;

  const expeditionFleetTaskId = dictionaries?.fleetTasksDictionary?.find(
    (task) => task.slug === "expedition"
  )?.id;

  const fleetsTrading = [...(fleets || []), ...(fleetsIncoming || [])].filter(
    (fleet) => fleet.fleetTaskId === tradeFleetTaskId
  );

  const fleetsExpedition = [
    ...(fleets || []),
    ...(fleetsIncoming || []),
  ].filter((fleet) => fleet.fleetTaskId === expeditionFleetTaskId);

  return (
    <SColumnFleets>
      <p>Active Fleets</p>
      {!fleets?.length && !fleetsIncoming?.length && <p>No Active Fleets</p>}

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

      {fleetsTrading && fleetsTrading.length > 0 && (
        <>
          <p>Trading Fleets</p>
          {fleetsTrading.map((fleet) => {
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

      {fleetsExpedition && fleetsExpedition.length > 0 && (
        <>
          <p>Expedition Fleets</p>
          {fleetsExpedition.map((fleet) => {
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
