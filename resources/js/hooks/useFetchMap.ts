import { useCustomQuery } from "./useCustomQuery";
import { IMap } from "../types/types";
import { useLocation } from "react-router-dom";

// TODO add types
export const useFetchMap = (queryParams?: any) => {
  const location = useLocation();
  const searchParams = new URLSearchParams(location.search);
  const isAdventure = searchParams.get("adventure") === "1";

  return useCustomQuery<IMap>({
    url: `/map`,
    queryParams: {
      ...queryParams,
      enabled: !isAdventure,
      refetchInterval: 10000,
    },
  });
};
