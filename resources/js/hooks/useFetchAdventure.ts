import { useCustomQuery } from "./useCustomQuery";
import { IMap, IMapAdventure } from "../types/types";
import { useLocation } from "react-router-dom";

// TODO add types
export const useFetchAdventure = (queryParams?: any) => {
  const location = useLocation();
  const searchParams = new URLSearchParams(location.search);
  const isAdventure = searchParams.get("adventure") === "1";

  return useCustomQuery<IMapAdventure>({
    url: `/map/adventure`,
    queryParams: {
      ...queryParams,
      enabled: isAdventure,
      refetchInterval: 10000,
    },
  });
};
