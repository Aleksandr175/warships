import { useCustomQuery } from "./useCustomQuery";
import { IMap } from "../types/types";

// TODO add types
export const useFetchMap = (queryParams?: any) => {
  return useCustomQuery<IMap>({
    url: `/map`,
    queryParams: {
      ...queryParams,
      refetchInterval: 10000,
    },
  });
};
