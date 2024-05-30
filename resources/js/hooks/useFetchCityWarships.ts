import { useCustomQuery } from "./useCustomQuery";
import { ICityWarshipsData } from "../types/types";
import { UseQueryResult } from "@tanstack/react-query";

export const useFetchCityWarships = (
  cityId?: number
): UseQueryResult<ICityWarshipsData> => {
  return useCustomQuery<ICityWarshipsData>({
    url: `/warships?cityId=${cityId}`,
    queryParams: {
      enabled: Boolean(cityId),
    },
  });
};
