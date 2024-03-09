import { useCustomQuery } from "./useCustomQuery";
import { IWarshipImprovements } from "../types/types";

// TODO add types
export const useFetchWarshipsImprovements = (queryParams?: any) => {
  return useCustomQuery<IWarshipImprovements>({
    url: `/warship-improvements`,
    queryParams: {
      ...queryParams,
    },
  });
};
