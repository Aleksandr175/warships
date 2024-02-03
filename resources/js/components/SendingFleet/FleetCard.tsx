import React, { useState } from "react";
import { useEffect } from "react";
import styled from "styled-components";
import { ICityWarship, IWarship } from "../../types/types";
import { InputNumber } from "../Common/InputNumber";
import { Warship } from "../Warships/Warship";

interface IProps {
  cityId: number;
  item: IWarship;
  cityWarship: ICityWarship | undefined;
  onChangeQty: (qty: number) => void;
}

export const FleetCard = ({ item, cityWarship, onChangeQty }: IProps) => {
  const [qty, setQty] = useState<number>(0);

  const maxShips = cityWarship?.qty || 0;

  useEffect(() => {
    onChangeQty(qty);
  }, [qty]);

  return (
    <SWarshipCardWrapper>
      <Warship warship={item} currentQty={maxShips}></Warship>

      <SInputWrapper>
        <InputNumberStyled
          value={qty}
          onChange={(value) => setQty(value)}
          maxNumber={maxShips}
          disabled={!maxShips}
        />
      </SInputWrapper>
    </SWarshipCardWrapper>
  );
};

const SInputWrapper = styled.div`
  margin-bottom: 10px;
`;

const SWarshipCardWrapper = styled.div``;

const InputNumberStyled = styled(InputNumber)`
  width: 132px;
`;
