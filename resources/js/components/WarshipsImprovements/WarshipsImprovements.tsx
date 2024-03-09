import React, { useEffect } from "react";
import { SContent, SH1 } from "../styles";
import styled from "styled-components";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useFetchWarshipsImprovements } from "../../hooks/useFetchWarshipsImprovements";
import { Card } from "../Common/Card";
import { Icon } from "../Common/Icon";
import { IUserResources, IWarshipImprovementRecipe } from "../../types/types";
import { useMutateWarshipImprovement } from "../../hooks/useMutateWarshipImprovement";
import { useFetchUserResources } from "../../hooks/useFetchUserResources";
import { useQueryClient } from "@tanstack/react-query";

export const WarshipsImprovements = () => {
  const queryDictionaries = useFetchDictionaries();
  const queryWarshipImprovements = useFetchWarshipsImprovements();
  const queryClient = useQueryClient();

  useEffect(() => {
    const intervalId = setInterval(() => {
      queryUserResources.refetch();
    }, 3000);

    return () => clearInterval(intervalId);
  }, []);

  const queryUserResources = useFetchUserResources();

  const getCurrentImprovementValue = (recipe: IWarshipImprovementRecipe) => {
    return (
      currentWarshipImprovements?.find(
        (improvement: any) =>
          improvement.improvementType === recipe.improvementType &&
          improvement.warshipId === recipe.warshipId
      )?.percentImprovement || 0
    );
  };

  const isImprovementAvailable = (
    availableCardsQty: number,
    recipe: IWarshipImprovementRecipe
  ) => {
    return availableCardsQty >= recipe.qty;
  };

  const availableCards = (recipe: IWarshipImprovementRecipe) => {
    return (
      userResources?.resources.find(
        (resource) => resource.resourceId === recipe.resourceId
      )?.qty || 0
    );
  };

  const {
    mutate: mutateWarshipImprovement,
    isPending: isImprovementInProgress,
    data: queryWarshipImprovementData,
  } = useMutateWarshipImprovement({
    onSuccess: (resp) => {
      queryClient.setQueryData(
        ["/user/resources"],
        (oldQueryData: IUserResources) => {
          return {
            ...oldQueryData,
            resources: resp.data.userResources,
          };
        }
      );
    },
  });

  const warshipImprovementData = queryWarshipImprovementData?.data;

  const currentWarshipImprovements =
    warshipImprovementData?.warshipImprovements ||
    queryWarshipImprovements?.data?.warshipImprovements;

  const warshipImprovementRecipes =
    warshipImprovementData?.warshipImprovementRecipes ||
    queryWarshipImprovements.data?.warshipImprovementRecipes;

  const userResources = queryUserResources?.data;

  if (queryWarshipImprovements.isLoading || queryDictionaries.isLoading) {
    return <></>;
  }

  return (
    <SContent>
      <SH1>Warships Improvements</SH1>

      <SImprovementsList>
        {warshipImprovementRecipes?.map((recipe) => {
          const availableCardsQty = availableCards(recipe);

          return (
            <SImprovementCard key={recipe.id}>
              <SCardWrapper>
                <Card
                  objectId={recipe.warshipId}
                  labelText={availableCardsQty + " / " + recipe.qty}
                  timer={0}
                  imagePath={"warships"}
                />
              </SCardWrapper>
              <SCardDescription>
                <SCardParams>
                  <Icon title={recipe.improvementType} />

                  <SImprovementParams>
                    {getCurrentImprovementValue(recipe)}%
                    <Icon title={"arrow"} />
                    {recipe.percentImprovement}
                  </SImprovementParams>
                </SCardParams>

                {isImprovementAvailable(availableCardsQty, recipe) && (
                  <SCardButton>
                    <button
                      disabled={isImprovementInProgress}
                      className={"btn btn-primary"}
                      onClick={() => {
                        mutateWarshipImprovement({ recipeId: recipe.id });
                      }}
                    >
                      Improve
                    </button>
                  </SCardButton>
                )}
              </SCardDescription>
            </SImprovementCard>
          );
        })}
      </SImprovementsList>
    </SContent>
  );
};

const SImprovementsList = styled.div`
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
`;

const SImprovementCard = styled.div`
  width: 18%;
  background: white;
  border-radius: var(--block-border-radius);
`;

const SCardWrapper = styled.div`
  height: 80px;
  border-radius: var(--block-border-radius-small)
    var(--block-border-radius-small) 0 0;
  overflow: hidden;
`;

const SCardDescription = styled.div`
  font-size: 10px;
  padding: 8px;
`;

const SCardParams = styled.div`
  display: flex;
  align-items: center;
  justify-content: space-between;
`;

const SImprovementParams = styled.div`
  display: flex;
  gap: 5px;
  align-items: center;
`;
const SCardButton = styled.div`
  margin-top: 8px;
  text-align: center;
`;
