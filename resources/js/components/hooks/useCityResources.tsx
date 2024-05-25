import { useQueryClient } from "@tanstack/react-query";
import { ICityResources } from "../../types/types";
import { useFetchCityResources } from "../../hooks/useFetchCityResources";

export const useCityResources = ({ cityId }: { cityId?: number }) => {
  const queryClient = useQueryClient();

  const queryCityResources = useFetchCityResources(cityId);

  const updateCityResourcesData = (newCityResourcesData: ICityResources) => {
    queryClient.setQueryData(["/city/" + newCityResourcesData.cityId], () => {
      return {
        cityId: newCityResourcesData.cityId,
        cityResources: newCityResourcesData.cityResources,
      };
    });
  };

  return {
    cityResources: queryCityResources?.data?.cityResources,
    updateCityResourcesData,
  };
};
