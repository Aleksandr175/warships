import { useQueryClient } from "@tanstack/react-query";
import { IResearchesData } from "../../types/types";
import { useFetchResearches } from "../../hooks/useFetchResearches";

export const useResearches = ({ cityId }: { cityId?: number }) => {
  const queryClient = useQueryClient();

  const queryResearches = useFetchResearches(cityId);

  const updateResearchesData = (newResearchesData: IResearchesData) => {
    queryClient.setQueryData(
      [`/researches`],
      (oldResearchesData: IResearchesData) => {
        return {
          ...oldResearchesData,
          ...newResearchesData,
        };
      }
    );
  };

  return {
    researches: queryResearches?.data?.researches,
    researchQueue: queryResearches?.data?.researchQueue,
    updateResearchesData,
  };
};
