import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled, { css } from "styled-components";
import {
  ICity,
  ICityWarship,
  IFleet,
  IFleetDetail,
  IWarship,
  TTask,
} from "../../types/types";
import { FleetCard } from "./FleetCard";
import { InputNumber } from "../Common/InputNumber";
import { SContent, SH1 } from "../styles";
import { useSearchParams } from "react-router-dom";

interface IProps {
  dictionary: IWarship[];
  warships: ICityWarship[] | undefined;
  cities: ICity[];
  city: ICity;
}

// TODO: add react hook form
export const Fleet = ({ dictionary, warships, cities, city }: IProps) => {
  const [taskType, setTaskType] = useState<TTask>("trade");
  const [coordX, setCoordX] = useState<number>(0);
  const [coordY, setCoordY] = useState<number>(0);
  const [actualCityWarships, setActualCityWarships] = useState(warships);
  const [gold, setGold] = useState(0);
  const [renderKey, setRenderKey] = useState(0);

  const [fleet, setFleet] = useState<IFleet>(() => {
    return {
      repeating: 0,
      taskType: "trade",
      cityId: city.id,
    } as IFleet;
  });
  const [fleetDetails, setFleetDetails] = useState<IFleetDetail[]>(
    [] as IFleetDetail[]
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
  }, [searchParams]);

  useEffect(() => {
    setActualCityWarships(warships);
  }, [warships]);

  // set default values
  useEffect(() => {
    const details = [] as IFleetDetail[];

    dictionary?.forEach((warship) => {
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
    httpClient
      .post("/fleets/send", {
        ...fleet,
        fleetDetails,
        gold,
        coordX,
        coordY,
        taskType,
        repeating,
      })
      .then((response) => {
        console.log(response);

        setActualCityWarships(response.data.warships);
        alert("Fleet has been sent");
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
      const dictWarship = dictionary.find(
        (warship) => warship.id === fDetail.warshipId
      );

      if (dictWarship) {
        maxCapacity += dictWarship.capacity * fDetail.qty;
      }
    });

    return maxCapacity;
  };

  const maxCapacity = getMaxCapacity();

  if (gold > maxCapacity) {
    setGold(maxCapacity);
  }

  return (
    <SContent>
      <SH1>Send Fleet</SH1>

      {dictionary.map((item) => {
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
        </div>

        {taskType !== "expedition" && (
          <div className={"col-12"}>
            <div>
              <strong>Target Island:</strong>
            </div>
            <div>
              {cities.map((city) => {
                return (
                  <SCityPreset
                    active={city.coordX === coordX && city.coordY === coordY}
                    onClick={() => chooseCity(city)}
                  >
                    {city.title}
                  </SCityPreset>
                );
              })}
            </div>

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
        <div className={"col-4"}>
          <div>
            <strong>Gold (Capacity: {maxCapacity}):</strong>
          </div>
          <InputNumberGoldStyled
            value={gold}
            onChange={(value) => setGold(value)}
            maxNumber={city.gold > maxCapacity ? maxCapacity : city.gold}
          />
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

        <div className={"col-12"}>
          <br />
          <button
            className={"btn btn-primary"}
            disabled={
              !taskType || (taskType !== "expedition" && (!coordY || !coordX))
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

const InputNumberGoldStyled = styled(InputNumber)`
  width: 100px;
`;
