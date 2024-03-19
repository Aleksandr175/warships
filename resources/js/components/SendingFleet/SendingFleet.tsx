import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled, { css } from "styled-components";
import {
  ICity,
  ICityResource,
  ICityWarship,
  IFleet,
  IFleetWarshipsData,
  TTask,
  TType,
} from "../../types/types";
import { FleetCard } from "./FleetCard";
import { InputNumber } from "../Common/InputNumber";
import { SContent, SH1 } from "../styles";
import { useSearchParams } from "react-router-dom";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { Icon } from "../Common/Icon";
import { useFetchWarshipsImprovements } from "../../hooks/useFetchWarshipsImprovements";
import { getWarshipImprovementPercent } from "../../utils";

interface IProps {
  cities: ICity[];
  city: ICity;
  cityResources: ICityResource[];
}

interface IResources {
  [slug: string]: number;
}

// TODO: add react hook form
export const SendingFleet = ({ cities, city, cityResources }: IProps) => {
  const queryDictionaries = useFetchDictionaries();
  const queryWarshipImprovements = useFetchWarshipsImprovements();

  const warshipImprovements =
    queryWarshipImprovements?.data?.warshipImprovements;

  const dictionaries = queryDictionaries.data;

  const [type, setType] = useState<TType>("map");
  const [taskType, setTaskType] = useState<TTask>("trade");
  const [coordX, setCoordX] = useState<number>(0);
  const [coordY, setCoordY] = useState<number>(0);
  const [warships, setWarships] = useState<ICityWarship[] | undefined>();
  const [actualCityWarships, setActualCityWarships] = useState(warships);

  const [resources, setResources] = useState<IResources>({});

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
    });
  };

  // TODO: refactor this shit
  const [renderKey, setRenderKey] = useState(0);

  const [fleet, setFleet] = useState<IFleet>(() => {
    return {
      repeating: 0,
      taskType: "trade",
      cityId: city.id,
    } as IFleet;
  });
  const [fleetDetails, setFleetDetails] = useState<IFleetWarshipsData[]>(
    [] as IFleetWarshipsData[]
  );

  const [searchParams, setSearchParams] = useSearchParams();

  useEffect(() => {
    if (searchParams.get("coordX")) {
      setCoordX(Number(searchParams.get("coordX")));
    }

    if (searchParams.get("coordY")) {
      setCoordY(Number(searchParams.get("coordY")));
    }

    if (searchParams.get("taskType")) {
      setTaskType(searchParams.get("taskType") as TTask);
    }

    if (searchParams.get("type")) {
      setType(searchParams.get("type") as TType);
    }
  }, [searchParams]);

  useEffect(() => {
    setActualCityWarships(warships);
  }, [warships]);

  // set default values
  useEffect(() => {
    const details = [] as IFleetWarshipsData[];

    dictionaries?.warshipsDictionary.forEach((warship) => {
      details.push({
        warshipId: warship.id,
        qty: 0,
      });
    });

    setFleetDetails(details);
  }, []);

  const [repeating, setRepeating] = useState(false);

  function getCityWarship(warshipId: number): ICityWarship | undefined {
    return actualCityWarships?.find(
      (warship) => warship.warshipId === warshipId
    );
  }

  const onChangeQty = (warshipId: number, qty: number) => {
    setFleetDetails((oldFleetDetails) => {
      let tempFleetDetails = [...oldFleetDetails];

      tempFleetDetails =
        tempFleetDetails?.map((detail) => {
          if (detail.warshipId === warshipId) {
            detail.qty = qty;
          }

          return detail;
        }) || [];

      return tempFleetDetails;
    });
  };

  const sendFleet = (): void => {
    const resourcesForFleet = Object.fromEntries(
      Object.entries(resources).filter(([key, value]) => value > 0)
    );

    httpClient
      .post("/fleets/send", {
        ...fleet,
        fleetDetails,
        resources: resourcesForFleet,
        coordX,
        coordY,
        taskType,
        repeating,
        type,
      })
      .then((response) => {
        console.log(response);

        setActualCityWarships(response.data.warships);
        console.log("Fleet has been sent");
        setRenderKey(renderKey + 1);
      });
  };

  const chooseCity = (city: ICity): void => {
    setCoordX(city.coordX);
    setCoordY(city.coordY);
  };

  const getMaxCapacity = (): number => {
    let maxCapacity = 0;

    fleetDetails?.forEach((fDetail) => {
      const dictWarship = dictionaries?.warshipsDictionary.find(
        (warship) => warship.id === fDetail.warshipId
      );

      if (dictWarship && warshipImprovements) {
        maxCapacity +=
          (dictWarship.capacity +
            Math.floor(
              (dictWarship.capacity *
                getWarshipImprovementPercent(
                  warshipImprovements,
                  dictWarship.id,
                  "capacity"
                )) /
                100
            )) *
          fDetail.qty;
      }
    });

    return maxCapacity;
  };

  const maxCapacity = getMaxCapacity();

  const getFreeCapacity = () => {
    let remainCapacity = maxCapacity;

    Object.entries(resources)?.forEach(([slug, value]) => {
      remainCapacity -= value;
    });

    return remainCapacity;
  };

  // 1 - common, 2 - card
  const cityResourcesDictionary = dictionaries?.resourcesDictionary?.filter(
    (resource) => resource.type === 1
  );

  if (!dictionaries || !warshipImprovements) {
    return null;
  }

  return (
    <SContent>
      <SH1>
        Send Fleet {type === "adventure" ? "to Adventure" : ""}
        {taskType === "trade" ? "to Trade" : ""}
      </SH1>

      {dictionaries.warshipsDictionary.map((item) => {
        return (
          <SItemWrapper key={item.id}>
            <FleetCard
              key={renderKey}
              cityId={city.id}
              item={item}
              cityWarship={getCityWarship(item.id)}
              onChangeQty={(qty) => onChangeQty(item.id, qty)}
            />
          </SItemWrapper>
        );
      })}

      <div className={"row"}>
        <div className={"col-12"}>
          <div>
            <strong>Task:</strong>
          </div>
          {/* TODO use dictionary for Task Types */}
          <STaskType
            active={taskType === "attack"}
            onClick={() => setTaskType("attack")}
          >
            Attack
          </STaskType>

          {type !== "adventure" && (
            <>
              <STaskType
                active={taskType === "trade"}
                onClick={() => setTaskType("trade")}
              >
                Trade
              </STaskType>
              <STaskType
                active={taskType === "transport"}
                onClick={() => setTaskType("transport")}
              >
                Transport
              </STaskType>
              <STaskType
                active={taskType === "move"}
                onClick={() => setTaskType("move")}
              >
                Move
              </STaskType>
              <STaskType
                active={taskType === "expedition"}
                onClick={() => setTaskType("expedition")}
              >
                Expedition
              </STaskType>
            </>
          )}
        </div>

        {taskType !== "expedition" && taskType !== "trade" && (
          <div className={"col-12"}>
            <div>
              <strong>Target Island:</strong>
            </div>
            {type === "map" && (
              <div>
                {cities.map((city) => {
                  return (
                    <SCityPreset
                      key={city.id}
                      active={city.coordX === coordX && city.coordY === coordY}
                      onClick={() => chooseCity(city)}
                    >
                      {city.title}
                    </SCityPreset>
                  );
                })}
              </div>
            )}

            <SCoordinatesBlock>
              <div>
                <strong>Or Coordinates:</strong>
              </div>
              <div>
                X:{" "}
                <InputNumberCoordinatesStyled
                  value={coordX}
                  onChange={(value) => setCoordX(value)}
                  maxNumber={100}
                />
                Y:{" "}
                <InputNumberCoordinatesStyled
                  value={coordY}
                  onChange={(value) => setCoordY(value)}
                  maxNumber={100}
                />
              </div>
            </SCoordinatesBlock>
          </div>
        )}

        {type === "map" && taskType !== "expedition" && taskType !== "trade" && (
          <>
            <div className={"col-12"}>
              <div>
                <strong>
                  Resources (Full Capacity: {maxCapacity}, Free Capacity:{" "}
                  {getFreeCapacity()}):
                </strong>
              </div>
              <div className={"row"}>
                {cityResourcesDictionary?.map((resource) => {
                  let maxAvailableAmount = maxCapacity;
                  const resourceQtyInCity =
                    cityResources.find(
                      (cityResource) => cityResource.resourceId === resource.id
                    )?.qty || 0;

                  if (maxAvailableAmount > resourceQtyInCity) {
                    maxAvailableAmount = resourceQtyInCity;
                  }

                  return (
                    <div className={"col-3"} key={resource.id}>
                      <Icon title={resource.slug} />
                      <InputNumberStyled
                        value={resources[resource.slug]}
                        onChange={(value) =>
                          setResources((prevState) => {
                            let remainCapacity = getFreeCapacity();

                            remainCapacity += resources?.[resource.slug] || 0;

                            if (value > remainCapacity) {
                              value = remainCapacity;
                            }

                            if (value > maxAvailableAmount) {
                              value = maxAvailableAmount;
                            }

                            return {
                              ...prevState,
                              [resource.slug]: value,
                            };
                          })
                        }
                      />
                    </div>
                  );
                })}
              </div>
            </div>

            <div className={"col-12"}>
              <div>
                <strong>Repeating:</strong>
              </div>
              <STaskType
                active={repeating}
                onClick={() => {
                  setRepeating(true);
                }}
              >
                Yes
              </STaskType>
              <STaskType
                active={!repeating}
                onClick={() => {
                  setRepeating(false);
                }}
              >
                No
              </STaskType>
            </div>
          </>
        )}

        <div className={"col-12"}>
          <br />
          <button
            className={"btn btn-primary"}
            disabled={
              getMaxCapacity() === 0 ||
              !taskType ||
              (taskType !== "expedition" &&
                taskType !== "trade" &&
                (!coordY || !coordX))
            }
            onClick={sendFleet}
          >
            Send Fleet
          </button>
        </div>
      </div>
    </SContent>
  );
};

const STaskType = styled.span<{ active?: boolean }>`
  cursor: pointer;
  display: inline-block;
  padding: 3px 8px;
  margin-right: 20px;
  border-radius: 5px;

  background: #39a0ff20;

  ${({ active }) =>
    active
      ? css`
          color: white;
          background-color: #6f4ca4;
        `
      : ""};
`;

const SCityPreset = styled.div<{ active?: boolean }>`
  cursor: pointer;
  display: inline-block;
  padding: 3px 8px;
  margin-right: 20px;
  border-radius: 5px;

  background: #39a0ff20;

  ${({ active }) =>
    active
      ? css`
          color: white;
          background-color: #6f4ca4;
        `
      : ""};
`;

const SCoordinatesBlock = styled.div`
  margin-top: var(--bs-gutter-y);
`;

const SItemWrapper = styled.div`
  display: inline-block;
`;

const InputNumberCoordinatesStyled = styled(InputNumber)`
  width: 50px;
  margin-right: 20px;
`;

const InputNumberStyled = styled(InputNumber)`
  width: 100px;
`;
