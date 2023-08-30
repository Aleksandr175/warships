import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled from "styled-components";
import { IMapCity } from "../../types/types";
import { SContent } from "../styles";

interface IProps {
  cityId: number;
}

export const Map = ({ cityId }: IProps) => {
  const [cities, setCities] = useState<IMapCity[]>([]);
  const [cells, setCells] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const size = 10 * 10;

  useEffect(() => {
    httpClient.get("/map").then((response) => {
      console.log(response);

      setCities(response.data.cities);

      let tCells = [];

      for (let i = 0; i < size; i++) {
        tCells[i] = {};
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

  if (isLoading) {
    return <>Loading...</>;
  }

  return (
    <SContent>
      <SCells>
        {cells.map((cell, index) => {
          const y = Math.floor(index / 10) + 1;
          const x = (index % 10) + 1;
          return <SCell isHabited={isCityHere(y, x)} />;
        })}
      </SCells>
    </SContent>
  );
};

const SCell = styled.div<{ isHabited?: boolean }>`
  display: block;
  float: left;
  width: 40px;
  height: 40px;
  border: 1px solid black;

  background-color: ${({ isHabited }) => (isHabited ? "#009900" : "none")};
`;

const SCells = styled.div`
  width: 400px;
  height: 400px;
`;
