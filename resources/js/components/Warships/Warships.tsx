import React, { useEffect, useState } from "react";
import {
  ICity,
  ICityResource,
  ICityWarship,
  ICityWarshipQueue,
  IWarshipImprovement,
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
import { httpClient } from "../../httpClient/httpClient";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  city: ICity;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
}

export const Warships = ({
  city,
  updateCityResources,
  cityResources,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const [selectedWarshipId, setSelectedWarshipId] = useState(0);

  const [warshipSlots, setWarshipSlots] = useState<number>(0);
  const [warshipQueue, setWarshipQueue] = useState<ICityWarshipQueue[]>([]);
  const [hasAvailableSlots, setHasAvailableSlots] = useState<boolean>(false);
  const [warships, setWarships] = useState<ICityWarship[] | undefined>();
  const [warshipImprovements, setWarshipImprovements] = useState<
    IWarshipImprovement[] | undefined
  >();

  useEffect(() => {
    if (dictionaries) {
      setSelectedWarshipId(dictionaries.warshipsDictionary[0]?.id || 0);
    }
  }, [dictionaries]);

  useEffect(() => {
    getWarships();

    const intervalId = setInterval(() => {
      getWarships();
    }, 3000);

    return () => clearInterval(intervalId);
  }, [city]);

  const getWarships = () => {
    if (!city?.id) {
      return;
    }

    httpClient.get("/warships?cityId=" + city?.id).then((response) => {
      setWarships(response.data.warships);
      setWarshipQueue(response.data.queue);
      setWarshipSlots(response.data.warshipSlots);
      setWarshipImprovements(response.data.warshipImprovements);
    });
  };

  useEffect(() => {
    setHasAvailableSlots(warshipQueue.length < warshipSlots);
  }, [warshipQueue]);

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
            cityResources={cityResources}
            getWarships={getWarships}
            setQueue={setWarshipQueue}
            researches={dictionaries.userResearches}
            getQty={getQty}
            setWarships={setWarships}
            updateCityResources={updateCityResources}
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

      {warshipQueue?.length > 0 && (
        <SContent>
          <WarshipsQueue
            queue={warshipQueue}
            sync={getWarships}
            warshipSlots={warshipSlots}
          />
        </SContent>
      )}
    </>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;
