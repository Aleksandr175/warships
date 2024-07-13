import { useQueryClient } from "@tanstack/react-query";
import { IFleetDataChanges, IFleetsData } from "../../types/types";
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

  const applyFleetsChangesData = (newFleetsData: IFleetDataChanges) => {
    queryClient.setQueryData([`/fleets`], (oldFleetsData: IFleetsData) => {
      // Extract relevant data from newFleetsData
      const {
        fleet,
        fleetChangeType,
        fleetDetails: newFleetDetails,
      } = newFleetsData;
      let updatedFleets = oldFleetsData.fleets || [];
      let updatedFleetDetails = oldFleetsData.fleetDetails || [];

      // Handle updates for fleets
      switch (fleetChangeType) {
        case "add":
          const fleetIndex = updatedFleets.findIndex((f) => f.id === fleet.id);
          if (fleetIndex > -1) {
            // Fleet already exists, update it
            updatedFleets = updatedFleets.map((f, index) =>
              index === fleetIndex ? { ...fleet } : f
            );
          } else {
            // Fleet does not exist, add new fleet to the array
            updatedFleets = [...updatedFleets, fleet];
          }
          break;
        case "remove":
          updatedFleets = updatedFleets.filter((f) => f.id !== fleet.id);
          updatedFleetDetails = updatedFleetDetails.filter(
            (fd) => fd.fleetId !== fleet.id
          );
          break;
        case "update":
          updatedFleets = updatedFleets.map((f) =>
            f.id === fleet.id ? { ...fleet } : f
          );
          break;
        default:
          break;
      }

      // Handle updates for fleet details using similar logic
      switch (fleetChangeType) {
        case "add":
          const detailIndex = updatedFleetDetails.findIndex(
            (fd) => fd.fleetId === fleet.id
          );
          if (detailIndex > -1) {
            updatedFleetDetails = updatedFleetDetails.map((fd, index) =>
              index === detailIndex ? { ...newFleetDetails[0] } : fd
            );
          } else {
            updatedFleetDetails = updatedFleetDetails.filter(
              (fd) => fd.fleetId !== fleet.id
            );

            updatedFleetDetails = [...updatedFleetDetails, ...newFleetDetails];
          }
          break;
        case "remove":
          updatedFleetDetails = updatedFleetDetails.filter(
            (fd) => fd.fleetId !== fleet.id
          );
          break;
        case "update":
          updatedFleetDetails = updatedFleetDetails.filter(
            (fd) => fd.fleetId !== fleet.id
          );

          updatedFleetDetails = [...updatedFleetDetails, ...newFleetDetails];
          break;
        default:
          break;
      }

      // TODO: merge cities correctly
      // Return the updated fleet data including updated fleet details
      return {
        ...oldFleetsData,
        ...newFleetsData,
        fleets: updatedFleets,
        fleetDetails: updatedFleetDetails,
      };
    });
  };

  return {
    fleets: queryFleets?.data?.fleets,
    fleetDetails: queryFleets?.data?.fleetDetails,
    fleetsIncoming: queryFleets?.data?.fleetsIncoming,
    fleetCities: queryFleets?.data?.cities,
    updateFleetsData,
    applyFleetsChangesData,
  };
};
