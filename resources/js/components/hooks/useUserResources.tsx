import { useFetchCityBuildings } from "../../hooks/useFetchCityBuildings";
import { useQueryClient } from "@tanstack/react-query";
import {
  ICityBuildingsData,
  IResource,
  IUserResources,
} from "../../types/types";
import { useFetchUserResources } from "../../hooks/useFetchUserResources";

export const useUserResources = () => {
  const queryClient = useQueryClient();

  const queryCityBuildings = useFetchUserResources();

  const updateUserResourcesData = (newQueryData: IResource[]) => {
    queryClient.setQueryData(
      ["/user/resources"],
      (oldQueryData: IUserResources) => {
        return {
          ...oldQueryData,
          resources: [...newQueryData],
        };
      }
    );
  };

  return {
    userResources: queryCityBuildings?.data?.resources,
    updateUserResourcesData,
  };
};
