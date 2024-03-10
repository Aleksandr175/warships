import React, { useRef } from "react";
import { ICityResources } from "../types/types";
import { Icon } from "./Common/Icon";
import styled from "styled-components";
import { getCityResourceProductionCoefficient } from "../utils";
import { useFetchDictionaries } from "../hooks/useFetchDictionaries";

export const CityResources = ({
  buildings,
  cityResources,
  city,
}: ICityResources) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const getProduction = (resourceId: number): number => {
    // find building which produces resourceId
    const buildingId =
      dictionaries?.buildingsProduction.find((bProduction) => {
        return bProduction.resourceId === resourceId;
      })?.buildingId || 0;

    if (buildingId) {
      // get lvl of building we have in city
      const lvl = buildings?.find(
        (building) => building.buildingId === buildingId
      )?.lvl;

      if (lvl) {
        const coefficient = getCityResourceProductionCoefficient(
          city,
          resourceId
        );

        const production =
          dictionaries?.buildingsProduction.find(
            (bProduction) =>
              bProduction.buildingId === buildingId &&
              bProduction.resourceId === resourceId &&
              bProduction.lvl === lvl
          )?.qty || 0;

        return production * coefficient;
      }
    }

    return 0;
  };

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
      {dictionaries?.resourcesDictionary?.map((resource) => {
        const resourceSlug = resource.slug;

        const cityResource = cityResources?.find(
          (cityResource) => cityResource.resourceId === resource.id
        );

        const production = Math.floor(getProduction(resource.id));

        return (
          <SResource key={resource.id}>
            <Icon title={resourceSlug} />
            {Math.floor(cityResource?.qty || 0)}{" "}
            {production > 0 && <SProduction>+{production}</SProduction>}
          </SResource>
        );
      })}
    </SResources>
  );
};

const SResources = styled.div`
  display: flex;
  flex-wrap: wrap;
  align-items: center;
`;

const SResource = styled.div`
  position: relative;
  display: flex;
  width: 24%;
  min-width: 24%;
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
