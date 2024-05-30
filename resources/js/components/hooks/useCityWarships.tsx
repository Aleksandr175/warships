import { useQueryClient } from "@tanstack/react-query";
import { ICityWarshipsData } from "../../types/types";
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

  return {
    warships: queryCityWarships?.data?.warships,
    warshipQueue: queryCityWarships?.data?.warshipQueue,
    warshipSlots: queryCityWarships?.data?.warshipSlots,
    warshipImprovements: queryCityWarships?.data?.warshipImprovements,
    updateCityWarshipsData,
  };
};
