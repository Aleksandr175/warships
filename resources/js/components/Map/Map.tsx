import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled, { css } from "styled-components";
import { IMapCity } from "../../types/types";
import { SContent } from "../styles";
import { useNavigate } from "react-router-dom";

interface IProps {
  cityId: number;
}

export const Map = ({ cityId }: IProps) => {
  const [cities, setCities] = useState<IMapCity[]>([]);
  const [cells, setCells] = useState<{ id: number }[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const size = 5 * 5;

  useEffect(() => {
    httpClient.get("/map").then((response) => {
      console.log(response);

      setCities(response.data.cities);

      let tCells = [];

      for (let i = 0; i < size; i++) {
        tCells[i] = {
          id: i,
        };
      }

      // @ts-ignore
      setCells(tCells);
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

  if (isLoading) {
    return <>Loading...</>;
  }

  return (
    <SContent>
      <SCells>
        {cells.map((cell, index) => {
          const y = Math.floor(index / 5) + 1;
          const x = (index % 5) + 1;
          const city = getCity(y, x);
          const isCity = isCityHere(y, x);
          const isPirates = city?.cityTypeId === 2;

          return (
            <SCell isHabited={isCity} key={cell.id}>
              <SIsland type={city?.cityAppearanceId} />
              {isCity && (
                <SIsland
                  islandType={isPirates ? "pirates" : ""}
                  type={city?.cityAppearanceId}
                  onClick={() =>
                    navigate(
                      "/fleets?coordX=" +
                        x +
                        "&coordY=" +
                        y +
                        "&taskType=attack"
                    )
                  }
                />
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
