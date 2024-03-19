import { useCustomQuery } from "./useCustomQuery";
import { IMapCity } from "../types/types";

// TODO add types
export const useFetchMap = (queryParams?: any) => {
  return useCustomQuery<{ cities: IMapCity[] }>({
    url: `/map`,
    queryParams: {
      ...queryParams,
      refetchInterval: 10000,
    },
  });
};
