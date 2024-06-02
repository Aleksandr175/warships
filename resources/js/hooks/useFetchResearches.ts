import { useCustomQuery } from "./useCustomQuery";
import { IResearchesData } from "../types/types";
import { UseQueryResult } from "@tanstack/react-query";

export const useFetchResearches = (
  cityId?: number
): UseQueryResult<IResearchesData, Error> => {
  return useCustomQuery<IResearchesData>({
    url: `/researches`,
    queryParams: {
      enabled: Boolean(cityId),
    },
  });
};
