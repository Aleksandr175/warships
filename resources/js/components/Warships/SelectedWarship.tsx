import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime } from "../../utils";
import React, { useRef, useState } from "react";
import styled from "styled-components";
import { httpClient } from "../../httpClient/httpClient";
import {
  IBuilding,
  ICityBuilding,
  ICityResources,
  ICityWarship,
  ICityWarshipQueue,
  IResearch,
  IUserResearch,
  IWarship,
  IWarshipDependency,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";

interface IProps {
  selectedWarshipId: number;
  cityId: number;
  warshipsDictionary: IWarship[];
  warshipDependencies: IWarshipDependency[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  setWarships: (warships: ICityWarship[]) => void;
  getWarships: () => void;
  queue?: ICityWarshipQueue[];
  setQueue: (q: ICityWarshipQueue[] | undefined) => void;
  getQty: (warshipId: number) => number;
  researchesDictionary: IResearch[];
  researches: IUserResearch[];
  buildingsDictionary: IBuilding[];
  buildings: ICityBuilding[];
}

export const SelectedWarship = ({
  selectedWarshipId,
  warshipsDictionary,
  warshipDependencies,
  cityId,
  buildings,
  buildingsDictionary,
  updateCityResources,
  cityResources,
  setQueue,
  researchesDictionary,
  researches,
  getQty,
  setWarships,
}: IProps) => {
  const [selectedQty, setSelectedQty] = useState(null);

  const selectedWarship = getWarship(selectedWarshipId)!;
  const warshipResources = getResourcesForWarship(selectedWarshipId);
  const gold = warshipResources?.gold || 0;
  const population = warshipResources?.population || 0;
  const time = warshipResources?.time || 0;
  const attack = warshipResources?.attack || 0;
  const speed = warshipResources?.speed || 0;
  const health = warshipResources?.health || 0;
  const capacity = warshipResources?.capacity || 0;

  function getResourcesForWarship(warshipId: number) {
    return warshipsDictionary.find((w) => w.id === warshipId);
  }

  function getWarship(warshipId: number): IBuilding | undefined {
    return warshipsDictionary.find((w) => w.id === warshipId);
  }

  let maxShips = 0;

  const maxShipsByGold = Math.floor(cityResources.gold / gold);
  const maxShipsByPopulation = Math.floor(
    cityResources.population / population
  );

  maxShips = Math.min(maxShipsByGold, maxShipsByPopulation);

  function isWarshipDisabled() {
    return gold > cityResources.gold || population > cityResources.population;
  }

  function run(warshipId: number, qty: number) {
    httpClient
      .post("/warships/create", {
        cityId,
        warshipId,
        qty,
      })
      .then((response) => {
        setWarships(response.data.warships);
        setQueue(response.data.queue);
        updateCityResources(response.data.cityResources);
      });
  }

  // TODO: add dependencies
  const {
    hasRequirements,
    hasAllRequirements,
    getRequirements,
    getRequiredItem,
  } = useRequirementsLogic({
    dependencyDictionary: warshipDependencies,
    buildingsDictionary,
    researchesDictionary,
    buildings,
    researches,
  });

  return (
    <SSelectedItem className={"row"}>
      <div className={"col-4"}>
        <SCardWrapper>
          <Card
            object={selectedWarship}
            qty={getQty(selectedWarshipId)}
            timer={
              0
              /*queue?.buildingId === selectedWarshipId ? timeLeft : 0 */
            }
            imagePath={"warships"}
          />
        </SCardWrapper>
      </div>
      <div className={"col-8"}>
        <SH2>{selectedWarship?.title}</SH2>
        <div>
          <SText>Required resources:</SText>
          <SParams>
            <SParam>
              <Icon title={"gold"} /> {gold}
            </SParam>
            <SParam>
              <Icon title={"worker"} /> {population}
            </SParam>
            <SParam>
              <Icon title={"time"} /> {convertSecondsToTime(time)}
            </SParam>
          </SParams>
        </div>
        <div>
          <SText>Warship Params:</SText>
          <SParams>
            <SParam>
              <Icon title={"capacity"} /> {capacity}
            </SParam>
            <SParam>
              <Icon title={"attack"} /> {attack}
            </SParam>
            <SParam>
              <Icon title={"heart"} /> {health}
            </SParam>
            <SParam>
              <Icon title={"speed"} /> {speed}
            </SParam>
          </SParams>
        </div>
        <div>
          <SText>You can build: {maxShips}</SText>
        </div>
        <SButtonsBlock>
          <SInput
            type="number"
            value={selectedQty || ""}
            onChange={(e) => {
              let number: string | number = e.currentTarget.value;

              if (!number) {
                number = 0;
              }

              number = parseInt(String(number), 10);

              if (number > 0) {
                if (number > maxShips) {
                  number = maxShips;
                }

                // @ts-ignore
                setSelectedQty(number);
              } else {
                setSelectedQty(null);
              }
            }}
          />
          <button
            className={"btn btn-primary"}
            disabled={
              isWarshipDisabled() ||
              !selectedQty ||
              !hasAllRequirements("warship", selectedWarshipId)
            }
            onClick={() => {
              run(selectedWarshipId, selectedQty ? selectedQty : 0);
              setSelectedQty(null);
            }}
          >
            Create
          </button>
        </SButtonsBlock>

        <SText>{selectedWarship?.description}</SText>

        {hasRequirements("warship", selectedWarshipId) && (
          <>
            <SText>It requires:</SText>
            {getRequirements("warship", selectedWarshipId)?.map(
              (requirement) => {
                const requiredItem = getRequiredItem(requirement);

                return (
                  <SText>
                    {requiredItem?.title}, {requirement.requiredEntityLvl} lvl
                  </SText>
                );
              }
            )}
          </>
        )}
      </div>
    </SSelectedItem>
  );
};

const SSelectedItem = styled.div`
  margin-bottom: calc(var(--block-gutter-y) * 2);
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;

const SInput = styled.input`
  display: inline-block;
  width: 80px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
`;
