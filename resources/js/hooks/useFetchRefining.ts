import { useCustomQuery } from "./useCustomQuery";
import { IRefiningData } from "../types/types";
import { UseQueryResult } from "@tanstack/react-query";

export const useFetchRefining = (
  cityId?: number
): UseQueryResult<IRefiningData> => {
  return useCustomQuery<IRefiningData>({
    url: `/refining?cityId=${cityId}`,
    queryParams: {
      enabled: Boolean(cityId),
    },
  });
};
