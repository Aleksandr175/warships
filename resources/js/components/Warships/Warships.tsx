import React, { useEffect, useState } from "react";
import {
  ICityBuilding,
  ICityResource,
  ICityWarship,
  ICityWarshipQueue,
  IUserResearch,
} from "../../types/types";
import { Warship } from "./Warship";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { WarshipsQueue } from "./WarshipsQueue";
import { SContent, SH1 } from "../styles";
import styled from "styled-components";
import { SelectedWarship } from "./SelectedWarship";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  cityId: number;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  warships: ICityWarship[] | undefined;
  setWarships: (warships: ICityWarship[]) => void;
  getWarships: () => void;
  queue?: ICityWarshipQueue[];
  setQueue: (q: ICityWarshipQueue[] | undefined) => void;
  researches: IUserResearch[];
  buildings: ICityBuilding[];
}

export const Warships = ({
  warships,
  setWarships,
  getWarships,
  cityId,
  updateCityResources,
  cityResources,
  queue,
  setQueue,
  researches,
  buildings,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const [selectedWarshipId, setSelectedWarshipId] = useState(0);

  useEffect(() => {
    if (dictionaries) {
      setSelectedWarshipId(dictionaries.warshipsDictionary[0]?.id || 0);
    }
  }, [dictionaries]);

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
            cityId={cityId}
            cityResources={cityResources}
            getWarships={getWarships}
            setQueue={setQueue}
            buildings={buildings}
            researches={researches}
            getQty={getQty}
            setWarships={setWarships}
            updateCityResources={updateCityResources}
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

      {queue && queue.length > 0 && (
        <SContent>
          <WarshipsQueue queue={queue} sync={getWarships} />
        </SContent>
      )}
    </>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;
