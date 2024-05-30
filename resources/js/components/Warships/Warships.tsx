import React, { useEffect, useState } from "react";
import { ICity, ICityResource } from "../../types/types";
import { Warship } from "./Warship";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { WarshipsQueue } from "./WarshipsQueue";
import { SContent, SH1 } from "../styles";
import styled from "styled-components";
import { SelectedWarship } from "./SelectedWarship";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useCityWarships } from "../hooks/useCityWarships";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  city: ICity;
  cityResources: ICityResource[];
}

export const Warships = ({ city, cityResources }: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const { warshipQueue, warshipImprovements, warshipSlots, warships } =
    useCityWarships({ cityId: city?.id });

  const [selectedWarshipId, setSelectedWarshipId] = useState(0);
  const [hasAvailableSlots, setHasAvailableSlots] = useState<boolean>(false);

  useEffect(() => {
    if (dictionaries) {
      setSelectedWarshipId(dictionaries.warshipsDictionary[0]?.id || 0);
    }
  }, [dictionaries]);

  useEffect(() => {
    setHasAvailableSlots((warshipQueue?.length || 0) < (warshipSlots || 0));
  }, [warshipQueue, warshipSlots]);

  function getQty(warshipId: number): number {
    return (
      warships?.find((warship) => warship.warshipId === warshipId)?.qty || 0
    );
  }

  if (!dictionaries) {
    return null;
  }

  return (
    <>
      <SContent>
        <SH1>Warships</SH1>
        {selectedWarshipId && (
          <SelectedWarship
            selectedWarshipId={selectedWarshipId}
            cityId={city.id}
            researches={dictionaries.userResearches}
            getQty={getQty}
            hasAvailableSlots={hasAvailableSlots}
            warshipImprovements={warshipImprovements}
          />
        )}

        {dictionaries.warshipsDictionary.map((item) => {
          const qty = getQty(item.id);

          return (
            <SItemWrapper
              key={item.id}
              onClick={() => {
                setSelectedWarshipId(item.id);
              }}
            >
              <Warship
                currentQty={qty}
                key={item.id}
                warship={item}
                selected={selectedWarshipId === item.id}
              />
            </SItemWrapper>
          );
        })}
      </SContent>

      {Boolean(warshipQueue?.length) && (
        <SContent>
          <WarshipsQueue cityId={city.id} />
        </SContent>
      )}
    </>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;
