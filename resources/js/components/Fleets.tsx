import React from "react";
import styled from "styled-components";
import { Fleet } from "./Fleet";
import {
  ICityFleet,
  IDictionary,
  IFleetWarshipsData,
  IMapCity,
  ICity,
} from "../types/types";
import { useFetchDictionaries } from "../hooks/useFetchDictionaries";

export const Fleets = ({
  fleets,
  fleetCitiesDictionary,
  fleetDetails,
  myCities,
}: {
  fleets: ICityFleet[] | undefined;
  dictionaries: IDictionary;
  fleetCitiesDictionary: IMapCity[];
  fleetDetails: IFleetWarshipsData[] | undefined;
  myCities: ICity[];
}) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const myCityIds = myCities.map((city) => city.id); // Extracting IDs for comparison

  const getFleetDetails = (fleetId: number): IFleetWarshipsData[] => {
    return fleetDetails?.filter((detail) => detail.fleetId === fleetId)!;
  };

  const tradeFleetTaskId = dictionaries?.fleetTasksDictionary?.find(
    (task) => task.slug === "trade"
  )?.id;

  const expeditionFleetTaskId = dictionaries?.fleetTasksDictionary?.find(
    (task) => task.slug === "expedition"
  )?.id;

  // Calculate incoming fleets based on the condition provided
  const fleetsIncoming = fleets?.filter(
    (fleet) =>
      !myCityIds.includes(fleet.cityId) &&
      myCityIds.includes(fleet.targetCityId)
  );

  const fleetsTrading = [...(fleets || []), ...(fleetsIncoming || [])].filter(
    (fleet) => fleet.fleetTaskId === tradeFleetTaskId
  );

  const fleetsExpedition = [
    ...(fleets || []),
    ...(fleetsIncoming || []),
  ].filter((fleet) => fleet.fleetTaskId === expeditionFleetTaskId);

  const activeFleets = fleets?.filter(
    (fleet) =>
      fleet.fleetTaskId !== expeditionFleetTaskId &&
      fleet.fleetTaskId !== tradeFleetTaskId
  );

  if (!dictionaries) {
    return <></>;
  }

  return (
    <SColumnFleets>
      {!activeFleets?.length && !fleetsIncoming?.length && (
        <p>No Active Fleets</p>
      )}

      {activeFleets && activeFleets.length > 0 && (
        <>
          <strong>Active Fleets</strong>
          {activeFleets.map((fleet) => {
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
          <strong>
            Expedition Fleets ({fleetsExpedition.length} /{" "}
            {dictionaries.maxFleetNumbers.expedition})
          </strong>
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
