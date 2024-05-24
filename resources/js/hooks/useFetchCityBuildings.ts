import { useCustomQuery } from "./useCustomQuery";
import { ICityBuildingsData } from "../types/types";
import { UseQueryResult } from "@tanstack/react-query";

export const useFetchCityBuildings = (
  cityId?: number
): UseQueryResult<ICityBuildingsData> => {
  return useCustomQuery<ICityBuildingsData>({
    url: `/buildings?cityId=${cityId}`,
    queryParams: {
      enabled: Boolean(cityId),
    },
  });
};
