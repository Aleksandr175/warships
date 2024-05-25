import { useCustomQuery } from "./useCustomQuery";
import { ICityResources } from "../types/types";
import { UseQueryResult } from "@tanstack/react-query";

export const useFetchCityResources = (
  cityId?: number
): UseQueryResult<ICityResources> => {
  return useCustomQuery<ICityResources>({
    url: `/city/${cityId}`,
    queryParams: {
      enabled: Boolean(cityId),
    },
  });
};
