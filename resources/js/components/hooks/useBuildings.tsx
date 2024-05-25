import { useFetchCityBuildings } from "../../hooks/useFetchCityBuildings";
import { useQueryClient } from "@tanstack/react-query";
import { ICityBuildingsData } from "../../types/types";

export const useBuildings = ({ cityId }: { cityId?: number }) => {
  const queryClient = useQueryClient();

  const queryCityBuildings = useFetchCityBuildings(cityId);

  const updateCityBuildingData = (newCityBuildingsData: ICityBuildingsData) => {
    queryClient.setQueryData(
      ["/buildings?cityId=" + newCityBuildingsData.cityId],
      () => {
        return {
          cityId: newCityBuildingsData.cityId,
          buildings: newCityBuildingsData.buildings,
          buildingQueue: newCityBuildingsData.buildingQueue || undefined,
        };
      }
    );
  };

  return {
    buildings: queryCityBuildings?.data?.buildings,
    buildingQueue: queryCityBuildings?.data?.buildingQueue || undefined,
    isCityBuildingsLoading: queryCityBuildings.isLoading,
    updateCityBuildingData,
  };
};
