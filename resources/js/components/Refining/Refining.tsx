import React, { useEffect, useState } from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { SContent, SH1, SH2 } from "../styles";
import styled, { css } from "styled-components";
import { Icon } from "../Common/Icon";
import { ProgressBar } from "../Common/ProgressBar";
import { httpClient } from "../../httpClient/httpClient";
import { ICity, ICityResource, IRefiningQueue } from "../../types/types";
import { useFetchRefiningRecipes } from "../../hooks/useFetchRefiningRecipes";
import { getResourceSlug, getTimeLeft } from "../../utils";
import { RefiningRecipe } from "./RefiningRecipe";

export const Refining = ({
  city,
  cityResources,
  updateCityResources,
}: {
  city: ICity;
  cityResources: ICityResource[];
  updateCityResources: (resources: ICityResource[]) => void;
}) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const numberOfSlots = 4; // TODO: get it from server

  const [refiningQueue, setRefiningQueue] = useState<IRefiningQueue[]>([]);
  const [hasAvailableSlots, setHasAvailableSlots] = useState<boolean>(false);

  const getRefiningQueue = () => {
    if (!city?.id) {
      return;
    }

    httpClient.get("/refining?cityId=" + city?.id).then((response) => {
      setRefiningQueue(response.data.refiningQueue);
    });
  };

  useEffect(() => {
    getRefiningQueue();

    setInterval(() => {
      getRefiningQueue();
    }, 3000);
  }, []);

  useEffect(() => {
    setHasAvailableSlots(refiningQueue.length < numberOfSlots);
  }, [refiningQueue]);

  console.log(hasAvailableSlots);
  const queryRefiningRecipes = useFetchRefiningRecipes();

  const renderSlots = () => {
    const slots = [];

    for (let i = 0; i < numberOfSlots; i++) {
      if (refiningQueue[i]) {
        const refining = refiningQueue[i];
        let timeLeft = getTimeLeft(refining.deadline);

        if (timeLeft < 0) {
          timeLeft = 0;
        }

        slots.push(
          <SRefiningSlot empty={false} key={refining.deadline}>
            <SRefiningMaterials>
              {dictionaries?.resourcesDictionary && (
                <SRefiningMaterialSlot>
                  <Icon
                    title={getResourceSlug(
                      dictionaries?.resourcesDictionary,
                      refining.inputResourceId
                    )}
                    size={"big"}
                  />
                </SRefiningMaterialSlot>
              )}
              <SRefiningMaterialTitle>
                {refining.outputQty}
              </SRefiningMaterialTitle>
              {dictionaries?.resourcesDictionary && (
                <SRefiningMaterialSlot>
                  <Icon
                    title={getResourceSlug(
                      dictionaries?.resourcesDictionary,
                      refining.outputResourceId
                    )}
                    size={"big"}
                  />
                </SRefiningMaterialSlot>
              )}
            </SRefiningMaterials>

            <SProgressWrapper>
              <ProgressBar
                percent={Math.ceil(
                  ((refining.time - timeLeft) / refining.time) * 100
                )}
              />
            </SProgressWrapper>
          </SRefiningSlot>
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
      <SH1>Refining Resources</SH1>
      <SRefiningSlots>{renderSlots()}</SRefiningSlots>

      <SH2>Refining Recipes</SH2>

      {queryRefiningRecipes?.data?.refiningRecipes?.map((recipe, index) => {
        return (
          <RefiningRecipe
            key={recipe.refiningRecipeId}
            recipe={recipe}
            city={city}
            cityResources={cityResources}
            setQueue={setRefiningQueue}
            updateCityResources={updateCityResources}
            hasAvailableSlots={hasAvailableSlots}
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

const SRefiningSlot = styled.div<{ empty?: boolean }>`
  position: relative;
  width: 160px;
  height: 160px;
  border-radius: var(--block-border-radius);
  background: rgb(0, 0, 0);
  background: linear-gradient(
    0deg,
    rgba(160, 205, 245, 1) 0%,
    rgba(42, 97, 244, 1) 100%
  );

  &:after {
    display: block;
    position: absolute;
    content: "";
    width: 100px;
    height: 100px;
    top: 50%;
    left: 50%;
    margin-top: -40px;
    margin-left: -50px;

    ${({ empty }) =>
      empty
        ? css`
            background: url("../../../images/icons/pot-empty.svg") no-repeat;
            background-size: contain;
          `
        : css`
            background: url("../../../images/icons/pot.svg") no-repeat;
            background-size: contain;
          `};
  }
`;

const SRefiningMaterials = styled.div`
  display: flex;
  margin-top: 10px;
  padding: 0 25px;
  justify-content: space-between;
  align-items: center;
`;

const SRefiningMaterialSlot = styled.div`
  width: 32px;
  height: 32px;
  border-radius: var(--block-border-radius);
  background: #e7ecfd;
`;

const SRefiningMaterialTitle = styled.div`
  color: white;
  font-size: 20px;
  width: 100%;
  text-align: center;
`;

const SProgressWrapper = styled.div`
  position: absolute;
  bottom: 10px;
  left: 10px;
  right: 10px;
`;
