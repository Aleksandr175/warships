import { useQueryClient } from "@tanstack/react-query";
import { IFleetsData } from "../../types/types";
import { useFetchFleets } from "../../hooks/useFetchFleets";

export const useFleets = () => {
  const queryClient = useQueryClient();

  const queryFleets = useFetchFleets();

  const updateFleetsData = (newFleetsData: IFleetsData) => {
    queryClient.setQueryData([`/fleets`], (oldFleetsData: IFleetsData) => {
      return {
        ...oldFleetsData,
        ...newFleetsData,
      };
    });
  };

  return {
    fleets: queryFleets?.data?.fleets,
    fleetDetails: queryFleets?.data?.fleetDetails,
    fleetsIncoming: queryFleets?.data?.fleetsIncoming,
    fleetCities: queryFleets?.data?.cities,
    updateFleetsData,
  };
};
