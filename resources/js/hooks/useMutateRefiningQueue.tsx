import { useMutation } from "@tanstack/react-query";
import { httpClient } from "../httpClient/httpClient";

// @TODO: add types
export const useMutateRefiningQueue = (options?: Object) => {
  return useMutation({
    mutationFn: ({
      recipeId,
      qty,
      cityId,
    }: {
      recipeId: number;
      cityId: number;
      qty: number;
    }) => {
      return httpClient.post(`/refining/run`, {
        cityId,
        qty,
        recipeId,
      });
    },
    ...options,
  });
};
