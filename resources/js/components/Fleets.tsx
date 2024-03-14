import React from "react";
import styled from "styled-components";
import { Fleet } from "./Fleet";
import {
  ICityFleet,
  IDictionary,
  IFleetWarshipsData,
  IFleetIncoming,
  IMapCity,
} from "../types/types";
import { useFetchDictionaries } from "../hooks/useFetchDictionaries";

export const Fleets = ({
  fleets,
  fleetsIncoming,
  fleetCitiesDictionary,
  fleetDetails,
}: {
  fleets: ICityFleet[] | undefined;
  fleetsIncoming: IFleetIncoming[] | undefined;
  dictionaries: IDictionary;
  fleetCitiesDictionary: IMapCity[];
  fleetDetails: IFleetWarshipsData[] | undefined;
}) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const getFleetDetails = (fleetId: number): IFleetWarshipsData[] => {
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

  if (!dictionaries) {
    return <></>;
  }

  return (
    <SColumnFleets>
      <strong>Active Fleets</strong>
      {!fleets?.length && !fleetsIncoming?.length && <p>No Active Fleets</p>}

      {fleets &&
        fleets.map((fleet) => {
          return (
            <Fleet
              key={fleet.id}
              fleet={fleet}
              fleetDetails={getFleetDetails(fleet.id)}
              // TODO: sent city and target city, not whole dictionary of cities
              fleetCities={fleetCitiesDictionary}
            />
          );
        })}

      {fleetsIncoming && fleetsIncoming.length > 0 && (
        <>
          <strong>Incoming Fleets</strong>
          {fleetsIncoming.map((fleet) => {
            return (
              <Fleet
                key={fleet.id}
                fleet={fleet}
                fleetDetails={getFleetDetails(fleet.id)}
                // TODO: sent city and target city, not whole dictionary of cities
                fleetCities={fleetCitiesDictionary}
              />
            );
          })}
        </>
      )}

      {fleetsTrading && fleetsTrading.length > 0 && (
        <>
          <strong>
            Trading Fleets ({fleetsTrading.length} /{" "}
            {dictionaries.maxFleetNumbers.trade})
          </strong>
          {fleetsTrading.map((fleet) => {
            return (
              <Fleet
                key={fleet.id}
                fleet={fleet}
                fleetDetails={getFleetDetails(fleet.id)}
                // TODO: sent city and target city, not whole dictionary of cities
                fleetCities={fleetCitiesDictionary}
              />
            );
          })}
        </>
      )}

      {fleetsExpedition && fleetsExpedition.length > 0 && (
        <>
          <strong>Expedition Fleets</strong>
          {fleetsExpedition.map((fleet) => {
            return (
              <Fleet
                key={fleet.id}
                fleet={fleet}
                fleetDetails={getFleetDetails(fleet.id)}
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
