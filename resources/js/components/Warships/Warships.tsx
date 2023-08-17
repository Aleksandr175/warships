import React, { useEffect, useRef, useState } from "react";
import { httpClient } from "../../httpClient/httpClient";
import {
  IBuilding,
  IBuildingResource,
  ICityResources,
  ICityWarship,
  ICityWarshipQueue,
  IWarship,
} from "../../types/types";
import { Warship } from "./Warship";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { WarshipsQueue } from "./WarshipsQueue";
import { SContent, SH1, SH2, SText } from "../styles";
import { Card } from "../Common/Card";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime } from "../../utils";
import styled from "styled-components";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  cityId: number;
  dictionary: IWarship[];
  resourcesDictionary: IBuildingResource[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  warships: ICityWarship[] | undefined;
  setWarships: (warships: ICityWarship[]) => void;
  getWarships: () => void;
  queue?: ICityWarshipQueue[];
  setQueue: (q: ICityWarshipQueue[] | undefined) => void;
}

export const Warships = ({
  warships,
  setWarships,
  getWarships,
  cityId,
  dictionary,
  updateCityResources,
  cityResources,
  queue,
  setQueue,
}: IProps) => {
  const [selectedWarshipId, setSelectedWarshipId] = useState(0);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();
  const [selectedQty, setSelectedQty] = useState(null);

  useEffect(() => {
    setSelectedWarshipId(dictionary[0]?.id || 0);
  }, [dictionary]);

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

  function getQty(warshipId: number): number {
    return (
      warships?.find((warship) => warship.warshipId === warshipId)?.qty || 0
    );
  }

  function getResourcesForWarship(warshipId: number) {
    return dictionary.find((w) => w.id === warshipId);
  }

  function getWarship(warshipId: number): IBuilding | undefined {
    return dictionary.find((warship) => warship.id === warshipId);
  }

  const selectedWarship = getWarship(selectedWarshipId);
  const warshipResources = getResourcesForWarship(selectedWarshipId);
  const gold = warshipResources?.gold || 0;
  const population = warshipResources?.population || 0;
  const time = warshipResources?.time || 0;
  const attack = warshipResources?.attack || 0;
  const speed = warshipResources?.speed || 0;
  const health = warshipResources?.health || 0;
  const capacity = warshipResources?.capacity || 0;

  let maxShips = 0;

  const maxShipsByGold = Math.floor(cityResources.gold / gold);
  const maxShipsByPopulation = Math.floor(
    cityResources.population / population
  );

  maxShips = Math.min(maxShipsByGold, maxShipsByPopulation);

  function isWarshipDisabled() {
    return gold > cityResources.gold || population > cityResources.population;
  }

  return (
    <>
      <SContent>
        <SH1>Warships</SH1>
        {selectedWarshipId && selectedWarship && (
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
              <br />
              <div>
                <SText>You can build: {maxShips}</SText>
              </div>
              <div>
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
                  disabled={isWarshipDisabled() || !selectedQty}
                  onClick={() => {
                    run(selectedWarshipId, selectedQty ? selectedQty : 0);
                    setSelectedQty(null);
                  }}
                >
                  Create
                </button>
              </div>

              <br />
              <br />
              <SText>{selectedWarship?.description}</SText>
            </div>
          </SSelectedItem>
        )}

        {dictionary.map((item) => {
          const qty = getQty(item.id);

          return (
            <SItemWrapper
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

      <SContent>
        {queue && queue.length > 0 && (
          <WarshipsQueue
            queue={queue}
            dictionary={dictionary}
            sync={getWarships}
          />
        )}
      </SContent>
    </>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;

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
  margin-bottom: 10px;
  width: 80px;
  border: none;
  border-radius: 5px;
`;

const SParams = styled.div`
  display: flex;
`;

const SParam = styled.div`
  width: 80px;
  color: #949494;
`;
