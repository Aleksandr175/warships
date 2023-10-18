import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled, { css } from "styled-components";
import { IMapCity } from "../../types/types";
import { SContent } from "../styles";

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
    console.log("mounted");
  }, []);

  const isCityHere = (y: number, x: number): boolean => {
    return (
      cities?.findIndex((city) => city.coordX === x && city.coordY === y) > -1
    );
  };

  const getCity = (y: number, x: number) => {
    return cities?.find((city) => city.coordX === x && city.coordY === y);
  };

  if (isLoading) {
    return <>Loading...</>;
  }

  return (
    <SContent>
      <SCells>
        {cells.map((cell, index) => {
          const y = Math.floor(index / 5) + 1;
          const x = (index % 5) + 1;
          return (
            <SCell isHabited={isCityHere(y, x)} key={cell.id}>
              <SIsland type={getCity(y, x)?.cityAppearanceId} />
              {isCityHere(y, x) && (
                <SIsland type={getCity(y, x)?.cityAppearanceId} />
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
  width: 100px;
  height: 100px;

  background: url("../../../images/islands/ocean.svg") no-repeat;
  background-size: contain;
`;

const SCells = styled.div`
  width: 500px;
  height: 500px;
`;

const SIsland = styled.div<{ type?: number }>`
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;

  ${({ type }) =>
    type
      ? css`
          background: url("../../../images/islands/${type}.svg") no-repeat;
          background-size: contain;
        `
      : ""};
`;
