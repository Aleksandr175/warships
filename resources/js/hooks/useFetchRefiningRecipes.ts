import { useCustomQuery } from "./useCustomQuery";
import { IRefiningRecipe } from "../types/types";
import { UseQueryOptions } from "@tanstack/react-query";

export const useFetchRefiningRecipes = (queryParams?: UseQueryOptions<any>) => {
  return useCustomQuery<{
    refiningRecipes: IRefiningRecipe[];
  }>({
    url: `/refining-recipes`,
    queryParams: {
      ...queryParams,
    },
  });
};
