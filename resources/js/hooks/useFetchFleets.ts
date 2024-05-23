import { useCustomQuery } from "./useCustomQuery";
import { IFleets } from "../types/types";

export const useFetchFleets = () => {
  return useCustomQuery<IFleets>({
    url: `/fleets`,
    queryParams: {},
  });
};
