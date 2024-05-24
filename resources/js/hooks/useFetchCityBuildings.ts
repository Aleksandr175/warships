import { useCustomQuery } from "./useCustomQuery";
import { ICityBuildingsData } from "../types/types";

export const useFetchCityBuildings = (cityId?: number) => {
  return useCustomQuery<ICityBuildingsData>({
    url: `/buildings?cityId=${cityId}`,
    queryParams: {
      enabled: Boolean(cityId),
    },
  });
};
