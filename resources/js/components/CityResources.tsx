import React, { useRef } from "react";
import { ICityResources } from "../types/types";
import { Icon } from "./Common/Icon";
import styled from "styled-components";
import { getResourceSlug } from "../utils";
import { useFetchDictionaries } from "../hooks/useFetchDictionaries";

export const CityResources = ({
  productions,
  cityResources,
}: ICityResources) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const timer = useRef<NodeJS.Timeout | null>(null);

  // temp
  /*const getGold = (): number => {
    const goldResourceId =
      resourcesDictionary?.find((resource) => resource.slug === "gold")?.id ||
      0;

    return (
      cityResources?.find((resource) => resource.resourceId === goldResourceId)
        ?.qty || 0
    );
  };

  const [goldValue, setGoldValue] = useState<number>(getGold() || 0);*/

  /*const updateGoldValue = useCallback(() => {
    setGoldValue((oldGoldValue) => {
      const production = (productionGold ?? 0) / 3600;
      return oldGoldValue + production;
    });
  }, [productionGold]);*/

  /*useEffect(() => {
    timer.current = setInterval(updateGoldValue, 1000);

    return () => {
      if (timer.current) clearInterval(timer.current);
    };
  }, [updateGoldValue]);*/

  /*useEffect(() => {
    setGoldValue(getGold());
  }, [cityResources]);*/

  /*const getResourceSlug = (resourcesDictionary, resourceId: number): string => {
    return (
      resourcesDictionary?.find((resource) => resource.id === resourceId)
        ?.slug || ""
    );
  };*/

  if (!dictionaries) {
    return null;
  }

  return (
    <SResources>
      {cityResources?.map((cityResource) => {
        const resourceSlug = getResourceSlug(
          dictionaries.resourcesDictionary,
          cityResource.resourceId
        );

        return (
          <SResource key={cityResource.resourceId}>
            <Icon title={resourceSlug} />
            {Math.floor(cityResource.qty)}{" "}
            {productions[resourceSlug]?.qty > 0 && (
              <SProduction>+{productions[resourceSlug]?.qty}</SProduction>
            )}
          </SResource>
        );
      })}
    </SResources>
  );
};

const SResources = styled.div`
  display: flex;
  align-items: center;
  gap: 20px;
`;

const SResource = styled.div`
  position: relative;
  display: flex;
  align-items: center;
`;

const SProduction = styled.span`
  position: relative;
  display: inline-block;
  top: -5px;
  padding-left: 5px;
  color: green;
  font-size: 12px;
  font-weight: 600;
`;
