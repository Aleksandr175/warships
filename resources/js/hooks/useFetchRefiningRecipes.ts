import { useCustomQuery } from "./useCustomQuery";
import { IRefiningRecipe } from "../types/types";

// TODO add types
export const useFetchRefiningRecipes = (queryParams?: any) => {
  return useCustomQuery<{
    refiningRecipes: IRefiningRecipe[];
  }>({
    url: `/refining-recipes`,
    queryParams: {
      ...queryParams,
    },
  });
};
