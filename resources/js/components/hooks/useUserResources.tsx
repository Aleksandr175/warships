import { useQueryClient } from "@tanstack/react-query";
import { useFetchUserResources } from "../../hooks/useFetchUserResources";
import {
  IUserResources,
  IUserResourcesChanges,
  IResource,
} from "../../types/types";

export const useUserResources = () => {
  const queryClient = useQueryClient();

  const queryUserResources = useFetchUserResources();

  const updateUserResourcesData = (newUserResources: IResource[]) => {
    queryClient.setQueryData(["/user/resources"], (oldData: IUserResources) => {
      return {
        ...oldData,
        resources: newUserResources,
      };
    });
  };

  const mergeUserResources = (
    oldData: IUserResources,
    changes: IUserResourcesChanges
  ) => {
    const resourceMap = new Map(
      oldData.resources.map((resource) => [resource.resourceId, resource])
    );

    changes.resourceChanges.forEach((change) => {
      if (resourceMap.has(change.resourceId)) {
        const existingResource = resourceMap.get(change.resourceId);
        // @ts-ignore
        existingResource.qty =
          // @ts-ignore
          Number(existingResource.qty) + Number(change.qty);
      } else {
        resourceMap.set(change.resourceId, { ...change });
      }
    });

    return {
      ...oldData,
      resources: Array.from(resourceMap.values()),
    };
  };

  const applyUserResourcesChanges = (
    userResourcesChanges: IUserResourcesChanges
  ) => {
    queryClient.setQueryData(["/user/resources"], (oldData: IUserResources) => {
      return mergeUserResources(oldData, userResourcesChanges);
    });
  };

  return {
    userResources: queryUserResources?.data?.resources,
    updateUserResourcesData,
    applyUserResourcesChanges,
  };
};
