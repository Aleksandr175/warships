import { useCustomQuery } from "./useCustomQuery";
import { IFleetsDataResponse } from "../types/types";

export const useFetchFleets = () => {
  return useCustomQuery<IFleetsDataResponse>({
    url: `/fleets`,
    queryParams: {},
  });
};
