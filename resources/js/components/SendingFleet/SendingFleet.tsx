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
  IMapCity,
  TTask,
  TType,
} from "../../types/types";
import { FleetCard } from "./FleetCard";
import { InputNumber } from "../Common/InputNumber";
import { SH1 } from "../styles";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { Icon } from "../Common/Icon";
import { useFetchWarshipsImprovements } from "../../hooks/useFetchWarshipsImprovements";
import { getWarshipImprovementPercent } from "../../utils";
import { useFleets } from "../hooks/useFleets";
import { useCityResources } from "../hooks/useCityResources";
import { useCityWarships } from "../hooks/useCityWarships";
import { toast } from "react-toastify";

interface IProps {
  city: ICity;
  cityResources: ICityResource[];
  fleetTask: TTask;
  targetCity?: IMapCity;
  isAdventure?: boolean;
  onSuccessSend: () => void;
}

interface IResources {
  [slug: string]: number;
}

// TODO: add react hook form
export const SendingFleet = ({
  city,
  targetCity,
  cityResources,
  fleetTask,
  isAdventure,
  onSuccessSend,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();
  const queryWarshipImprovements = useFetchWarshipsImprovements();

  const warshipImprovements =
    queryWarshipImprovements?.data?.warshipImprovements;

  const dictionaries = queryDictionaries.data;

  const [type, setType] = useState<TType>(isAdventure ? "adventure" : "map");
  const [taskType, setTaskType] = useState<TTask>(fleetTask);
  const [actualCityWarships, setActualCityWarships] = useState<ICityWarship[]>(
    []
  );

  const [resources, setResources] = useState<IResources>({});

  const { updateFleetsData } = useFleets();
  const { updateCityResourcesData } = useCityResources({ cityId: city.id });
  const { warships, updateCityWarshipsData } = useCityWarships({
    cityId: city.id,
  });

  const notify = () => toast.success("Fleet sent");

  // TODO: refactor this shit
  const [renderKey, setRenderKey] = useState(0);

  const [fleet, setFleet] = useState<IFleet>(() => {
    return {
      repeating: 0,
      taskType,
      cityId: city.id,
    } as IFleet;
  });

  useEffect(() => {
    setFleet((oldFleetData) => {
      return {
        ...oldFleetData,
        cityId: city.id,
      };
    });
  }, [city]);

  const [fleetDetails, setFleetDetails] = useState<IFleetWarshipsData[]>(
    [] as IFleetWarshipsData[]
  );

  useEffect(() => {
    if (warships) {
      setActualCityWarships(warships);
    }
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
        targetCityId: targetCity?.id,
        taskType,
        repeating,
        type,
      })
      .then((response) => {
        console.log(response);

        updateFleetsData({
          fleets: response.data.fleets,
          fleetDetails: response.data.fleetDetails,
          fleetsIncoming: response.data.fleetsIncoming,
          cities: response.data.cities,
        });

        updateCityResourcesData({
          cityResources: response.data.cityResources,
          cityId: response.data.cityId,
        });

        updateCityWarshipsData({
          cityId: response.data.cityId,
          warships: response.data.cityWarships,
        });

        setResources({});
        setRenderKey(renderKey + 1);
        notify();
        onSuccessSend();
      });
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

  // TODO: add constants
  // 1 - common, 2 - card
  const cityResourcesDictionary = dictionaries?.resourcesDictionary?.filter(
    (resource) => resource.type === 1
  );

  if (!dictionaries || !warshipImprovements) {
    return null;
  }

  return (
    <>
      <SH1>
        Send Fleet {type === "adventure" ? "to Adventure" : ""}
        {taskType === "trade" ? "to Trade" : ""}
        {taskType === "move" ? "to Move" : ""}
        {taskType === "transport" ? "to Transport" : ""}
        {taskType === "attack" ? "to Attack" : ""}
        {taskType === "expedition" ? "to Expedition" : ""}
        {taskType === "takeOver" ? "to Take Over" : ""}
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
        {type === "map" && taskType === "attack" && (
          <div className={"col-12"}>
            <strong>Resources (Full Capacity: {maxCapacity})</strong>
          </div>
        )}

        {type === "map" &&
          taskType !== "expedition" &&
          taskType !== "trade" &&
          taskType !== "attack" && (
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
                        (cityResource) =>
                          cityResource.resourceId === resource.id
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
                taskType !== "attack" &&
                !targetCity)
            }
            onClick={sendFleet}
          >
            Send Fleet
          </button>
        </div>
      </div>
    </>
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

const SItemWrapper = styled.div`
  display: inline-block;
`;

const InputNumberStyled = styled(InputNumber)`
  width: 100px;
`;
