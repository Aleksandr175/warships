import React from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { SContent, SH1, SH2 } from "../styles";
import styled from "styled-components";
import { ICity, ICityResource } from "../../types/types";
import { useFetchRefiningRecipes } from "../../hooks/useFetchRefiningRecipes";
import { getTimeLeft } from "../../utils";
import { RefiningRecipe } from "./RefiningRecipe";
import { useCityRefining } from "../hooks/useCityRefining";
import {
  RefiningSlot,
  SRefiningMaterials,
  SRefiningMaterialTitle,
  SRefiningSlot,
} from "./RefiningSlot";

export const Refining = ({
  city,
  cityResources,
}: {
  city: ICity;
  cityResources: ICityResource[];
}) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const { refiningQueue, refiningSlots } = useCityRefining({
    cityId: city?.id,
  });

  const queryRefiningRecipes = useFetchRefiningRecipes();

  const renderSlots = () => {
    const slots = [];

    for (let i = 0; i < refiningSlots; i++) {
      if (refiningQueue[i]) {
        const refining = refiningQueue[i];
        let timeLeft = getTimeLeft(refining.deadline);

        if (timeLeft < 0) {
          timeLeft = 0;
        }

        slots.push(
          <RefiningSlot
            data={refining}
            key={refining.deadline + "-" + refining.cityId}
          />
        );
      } else {
        slots.push(
          <SRefiningSlot empty={true} key={i}>
            <SRefiningMaterials>
              <SRefiningMaterialTitle>Free Slot</SRefiningMaterialTitle>
            </SRefiningMaterials>
          </SRefiningSlot>
        );
      }
    }

    return slots;
  };

  if (!dictionaries) {
    return null;
  }

  return (
    <SContent>
      <SH1>
        Refining Resources ({refiningQueue.length} / {refiningSlots})
      </SH1>
      <SRefiningSlots>{renderSlots()}</SRefiningSlots>

      <SH2>Refining Recipes</SH2>

      {queryRefiningRecipes?.data?.refiningRecipes?.map((recipe, index) => {
        return (
          <RefiningRecipe
            key={recipe.refiningRecipeId}
            recipe={recipe}
            city={city}
            cityResources={cityResources}
          />
        );
      })}
    </SContent>
  );
};

const SRefiningSlots = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 40px;
`;
