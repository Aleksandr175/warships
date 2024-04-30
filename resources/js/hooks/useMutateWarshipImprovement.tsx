import { useMutation, UseMutationOptions } from "@tanstack/react-query";
import { httpClient } from "../httpClient/httpClient";
import { AxiosResponse } from "axios";
import {
  IResource,
  IWarshipImprovement,
  IWarshipImprovementRecipe,
} from "../types/types";

interface IMutateWarshipImprovementRequest {
  recipeId: number;
}
interface IMutateWarshipImprovementResponse {
  userResources: IResource[];
  warshipImprovementRecipes: IWarshipImprovementRecipe[];
  warshipImprovements: IWarshipImprovement[];
}

export const useMutateWarshipImprovement = (
  options?: UseMutationOptions<
    AxiosResponse<IMutateWarshipImprovementResponse>,
    unknown,
    IMutateWarshipImprovementRequest,
    unknown
  >
) => {
  return useMutation<
    AxiosResponse<IMutateWarshipImprovementResponse>,
    unknown,
    IMutateWarshipImprovementRequest,
    unknown
  >({
    mutationFn: ({
      recipeId,
    }: IMutateWarshipImprovementRequest): Promise<
      AxiosResponse<IMutateWarshipImprovementResponse>
    > => {
      return httpClient.post(`/warship-improvements`, {
        recipeId,
      });
    },
    ...options,
  });
};
