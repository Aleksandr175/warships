import { useCustomQuery } from "./useCustomQuery";
import { IWarshipImprovements } from "../types/types";
import { UseQueryOptions } from "@tanstack/react-query";

export const useFetchWarshipsImprovements = (
  queryParams?: UseQueryOptions<any>
) => {
  return useCustomQuery<IWarshipImprovements>({
    url: `/warship-improvements`,
    queryParams: {
      ...queryParams,
    },
  });
};
