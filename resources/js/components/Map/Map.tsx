import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled, { css } from "styled-components";
import { IMapCity, TType } from "../../types/types";
import { SContent, SH1 } from "../styles";
import { useNavigate } from "react-router-dom";
import { Icon } from "../Common/Icon";

export const Map = () => {
  const size = 5 * 5;
  const [cities, setCities] = useState<IMapCity[]>([]);
  const [type, setType] = useState<TType>("map");
  const [cells, setCells] = useState<{ id: number }[]>(() => {
    let tCells = [];

    for (let i = 0; i < size; i++) {
      tCells[i] = {
        id: i,
      };
    }

    return tCells;
  });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    httpClient.get("/map").then((response) => {
      setCities(response.data.cities);

      setIsLoading(false);
    });
  }, []);

  const isCityHere = (y: number, x: number): boolean => {
    return (
      cities?.findIndex((city) => city.coordX === x && city.coordY === y) > -1
    );
  };

  const getCity = (y: number, x: number) => {
    return cities?.find((city) => city.coordX === x && city.coordY === y);
  };

  const navigate = useNavigate();

  const getAdventure = () => {
    setIsLoading(true);
    setType("adventure");
    httpClient.get("/map/adventure").then((response) => {
      setCities(response.data.cities);

      setIsLoading(false);
    });
  };

  const isFleetMovingToIsland = (cityId: number): boolean => {
    return false;
  };

  const isIslandRaided = (cityId: number) => {
    return !!cities.find((city) => city.id === cityId)?.raided;
  };

  if (isLoading) {
    return <>Loading...</>;
  }

  return (
    <SContent>
      <button className={"btn btn-primary"} onClick={getAdventure}>
        Adventure
      </button>

      <SH1>{type === "map" ? "Map" : "Adventure"}</SH1>
      <SCells>
        {cells?.map((cell, index) => {
          const y = Math.floor(index / 5) + 1;
          const x = (index % 5) + 1;
          const city = getCity(y, x);
          const isCity = isCityHere(y, x);
          const isPirates = city?.cityTypeId === 2;

          return (
            <SCell isHabited={isCity} key={cell.id}>
              {city && isCity && (
                <>
                  <SIsland
                    islandType={isPirates ? "pirates" : ""}
                    type={city?.cityAppearanceId}
                    onClick={() =>
                      navigate(
                        `/fleets?coordX=${x}&coordY=${y}&taskType=attack&type=${type}`
                      )
                    }
                  />
                  {isFleetMovingToIsland(city?.id) && (
                    <SCityMarkFleet>
                      <Icon title={"moving"} />
                    </SCityMarkFleet>
                  )}
                  {isIslandRaided(city?.id) && (
                    <SCityMarkStatus>
                      <Icon title={"check"} />
                    </SCityMarkStatus>
                  )}
                </>
              )}
            </SCell>
          );
        })}
      </SCells>
    </SContent>
  );
};

const SCell = styled.div<{ isHabited?: boolean }>`
  position: relative;
  float: left;
  width: 140px;
  height: 140px;

  background: url("../../../images/islands/ocean.svg") no-repeat;
  background-size: contain;
`;

const SCells = styled.div`
  width: 700px;
  height: 700px;
`;

const SIsland = styled.div<{ type?: number; islandType?: string }>`
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;

  ${({ type, islandType }) =>
    type
      ? css`
          background: url("../../../images/islands/${islandType
              ? "/" + islandType + "/"
              : ""}${type}.svg")
            no-repeat;
          background-size: contain;
        `
      : ""};
`;

const SCityMarkFleet = styled.div`
  position: absolute;
  top: 0;
  left: 0;
  width: 32px;
  height: 32px;
`;
const SCityMarkStatus = styled.div`
  position: absolute;
  top: 0;
  right: 0;
  width: 32px;
  height: 32px;
`;
