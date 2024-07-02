import { useCustomQuery } from "./useCustomQuery";
import { IResearchImprovement } from "../types/types";
import { UseQueryOptions } from "@tanstack/react-query";

interface IResearchImprovements {
  researchImprovements: IResearchImprovement[];
}

export const useFetchResearchImprovements = (
  queryParams?: UseQueryOptions<any>
) => {
  return useCustomQuery<IResearchImprovements>({
    url: `/research-improvements`,
    queryParams: {
      ...queryParams,
    },
  });
};
