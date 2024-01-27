import { UseQueryOptions } from "@tanstack/react-query";
import { useCustomQuery } from "./useCustomQuery";
import { IDictionary } from "../types/types";

// TODO add types
export const useFetchDictionaries = (queryParams?: any) => {
  return useCustomQuery<IDictionary>({
    url: `/dictionaries`,
    queryParams: {
      ...queryParams,
    },
  });
};
