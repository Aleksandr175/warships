import React, { useEffect, useState } from "react";
import {
  IBuilding,
  ICityBuilding,
  ICityResource,
  ICityWarship,
  ICityWarshipQueue,
  IResearch,
  IResourceDictionary,
  IUserResearch,
  IWarship,
  IWarshipDependency,
} from "../../types/types";
import { Warship } from "./Warship";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { WarshipsQueue } from "./WarshipsQueue";
import { SContent, SH1 } from "../styles";
import styled from "styled-components";
import { SelectedWarship } from "./SelectedWarship";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  cityId: number;
  dictionary: IWarship[];
  resourcesDictionary: IResourceDictionary[];
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  warships: ICityWarship[] | undefined;
  setWarships: (warships: ICityWarship[]) => void;
  getWarships: () => void;
  queue?: ICityWarshipQueue[];
  setQueue: (q: ICityWarshipQueue[] | undefined) => void;
  warshipDependencies: IWarshipDependency[];
  researches: IUserResearch[];
  researchesDictionary: IResearch[];
  buildings: ICityBuilding[];
  buildingsDictionary: IBuilding[];
}

export const Warships = ({
  warships,
  setWarships,
  getWarships,
  cityId,
  dictionary,
  warshipDependencies,
  updateCityResources,
  cityResources,
  queue,
  setQueue,
  researches,
  researchesDictionary,
  buildings,
  buildingsDictionary,
  resourcesDictionary,
}: IProps) => {
  const [selectedWarshipId, setSelectedWarshipId] = useState(0);

  useEffect(() => {
    setSelectedWarshipId(dictionary[0]?.id || 0);
  }, [dictionary]);

  function getQty(warshipId: number): number {
    return (
      warships?.find((warship) => warship.warshipId === warshipId)?.qty || 0
    );
  }

  return (
    <>
      <SContent>
        <SH1>Warships</SH1>
        {selectedWarshipId && (
          <SelectedWarship
            selectedWarshipId={selectedWarshipId}
            cityId={cityId}
            warshipsDictionary={dictionary}
            warshipDependencies={warshipDependencies}
            cityResources={cityResources}
            getWarships={getWarships}
            setQueue={setQueue}
            researchesDictionary={researchesDictionary}
            buildings={buildings}
            buildingsDictionary={buildingsDictionary}
            researches={researches}
            getQty={getQty}
            setWarships={setWarships}
            updateCityResources={updateCityResources}
            resourcesDictionary={resourcesDictionary}
          />
        )}

        {dictionary.map((item) => {
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
          <WarshipsQueue
            queue={queue}
            dictionary={dictionary}
            sync={getWarships}
          />
        </SContent>
      )}
    </>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;
