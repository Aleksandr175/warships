import { useQueryClient } from "@tanstack/react-query";
import { IRefiningData } from "../../types/types";
import { useFetchRefining } from "../../hooks/useFetchRefining";

export const useCityRefining = ({ cityId }: { cityId?: number }) => {
  const queryClient = useQueryClient();

  const queryRefining = useFetchRefining(cityId);

  const updateCityRefiningData = (newRefiningData: IRefiningData) => {
    queryClient.setQueryData(
      ["/refining?cityId=" + newRefiningData.cityId],
      () => {
        return {
          cityId: newRefiningData.cityId,
          refiningQueue: newRefiningData.refiningQueue,
          refiningSlots: newRefiningData.refiningSlots,
        };
      }
    );
  };

  return {
    refiningQueue: queryRefining?.data?.refiningQueue || [],
    refiningSlots: queryRefining?.data?.refiningSlots || 0,
    updateCityRefiningData,
  };
};
