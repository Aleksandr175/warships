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

  const mergeCityResources = (
    oldData: ICityResources,
    changes: ICityResourcesChanges
  ) => {
    const resourceMap = new Map(
      oldData?.cityResources?.map((resource) => [resource.resourceId, resource])
    );

    changes.cityResourcesChanges.forEach((change) => {
      if (resourceMap.has(change.resourceId)) {
        const existingResource = resourceMap.get(change.resourceId);
        // @ts-ignore
        existingResource.qty =
          // @ts-ignore
          Number(existingResource.qty) + Number(change.qty);
      } else {
        // Assuming you might also need to add new resources not previously listed
        resourceMap.set(change.resourceId, { ...change, cityId: cityId || 0 });
      }
    });

    return {
      ...oldData,
      cityResources: Array.from(resourceMap.values()),
    };
  };

  const applyCityResourcesChangesData = (
    cityResourcesChanges: ICityResourcesChanges
  ) => {
    queryClient.setQueryData(
      ["/city/" + cityResourcesChanges.cityId],
      (oldData: ICityResources) => {
        return mergeCityResources(oldData, cityResourcesChanges);
      }
    );
  };

  return {
    cityResources: queryCityResources?.data?.cityResources,
    updateCityResourcesData,
    applyCityResourcesChangesData,
  };
};
