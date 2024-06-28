import { useQueryClient } from "@tanstack/react-query";
import {
  ICityWarship,
  ICityWarshipsData,
  ICityWarshipsDataChanges,
} from "../../types/types";
import { useFetchCityWarships } from "../../hooks/useFetchCityWarships";

export const useCityWarships = ({ cityId }: { cityId?: number }) => {
  const queryClient = useQueryClient();

  const queryCityWarships = useFetchCityWarships(cityId);

  const updateCityWarshipsData = (newCityWarshipsData: ICityWarshipsData) => {
    queryClient.setQueryData(
      [`/warships?cityId=${newCityWarshipsData.cityId}`],
      (oldCityWarshipsData: ICityWarshipsData) => {
        return {
          ...oldCityWarshipsData,
          ...newCityWarshipsData,
        };
      }
    );
  };

  const mergeWarships = (
    oldData: ICityWarshipsData,
    changes: ICityWarshipsDataChanges
  ): ICityWarshipsData => {
    // Create a map from the old data for quick lookup
    const warshipMap = new Map<number, ICityWarship>(
      oldData.warships?.map((warship) => [warship.warshipId, warship]) ?? []
    );

    // Apply changes
    changes.warships.forEach((change) => {
      if (warshipMap.has(change.warshipId)) {
        const existingWarship = warshipMap.get(change.warshipId)!;
        existingWarship.qty += change.qty;
      } else {
        // Add new warship with the current cityId
        warshipMap.set(change.warshipId, { ...change, cityId: changes.cityId });
      }
    });

    // Convert map back to array
    const updatedWarships = Array.from(warshipMap.values());

    // Return new data object with updated warships
    return { ...oldData, warships: updatedWarships };
  };

  const applyCityWarshipsDataChanges = (
    newCityWarshipsDataChanges: ICityWarshipsDataChanges
  ) => {
    queryClient.setQueryData(
      [`/warships?cityId=${newCityWarshipsDataChanges.cityId}`],
      (oldCityWarshipsData: ICityWarshipsData) =>
        mergeWarships(oldCityWarshipsData, newCityWarshipsDataChanges)
    );
  };

  return {
    warships: queryCityWarships?.data?.warships,
    warshipQueue: queryCityWarships?.data?.warshipQueue,
    warshipSlots: queryCityWarships?.data?.warshipSlots,
    warshipImprovements: queryCityWarships?.data?.warshipImprovements,
    updateCityWarshipsData,
    applyCityWarshipsDataChanges,
  };
};
