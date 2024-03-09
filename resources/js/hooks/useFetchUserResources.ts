import { useCustomQuery } from "./useCustomQuery";
import { IUserResources } from "../types/types";
import { UseQueryOptions } from "@tanstack/react-query";

export const useFetchUserResources = (queryParams?: UseQueryOptions<any>) => {
  return useCustomQuery<IUserResources>({
    url: `/user/resources`,
    queryParams: {
      ...queryParams,
    },
  });
};
