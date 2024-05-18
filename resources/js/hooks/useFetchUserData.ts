import { useCustomQuery } from "./useCustomQuery";
import { IUserData } from "../types/types";

// TODO add types
export const useFetchUserData = (queryParams?: any) => {
  return useCustomQuery<IUserData>({
    url: `/user`,
    queryParams: {
      ...queryParams,
    },
  });
};
