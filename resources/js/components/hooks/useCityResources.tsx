import { useQueryClient } from "@tanstack/react-query";
import {
  ICityResource,
  ICityResources,
  ICityResourcesChanges,
} from "../../types/types";
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

  const applyCityResourcesChangesData = (
    cityResourcesChanges: ICityResourcesChanges
  ) => {
    queryClient.setQueryData(
      ["/city/" + cityResourcesChanges.cityId],
      (oldData: { cityResources: ICityResource[] }) => {
        const updatedResources = oldData.cityResources.map((resource) => {
          const change = cityResourcesChanges.cityResourcesChanges.find(
            (c) => c.resourceId === resource.resourceId
          );
          return {
            ...resource,
            qty: change ? resource.qty + change.qty : resource.qty,
          };
        });

        return {
          cityId: cityResourcesChanges.cityId,
          cityResources: updatedResources,
        };
      }
    );
  };

  return {
    cityResources: queryCityResources?.data?.cityResources,
    updateCityResourcesData,
    applyCityResourcesChangesData,
  };
};
