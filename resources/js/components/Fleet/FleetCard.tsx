import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled from "styled-components";
import { ICityWarship, IMapCity, IWarship } from "../../types/types";
import { Warship } from "../Warships/Warship";
import { Card } from "../Common/Card";

interface IProps {
    cityId: number;
    item: IWarship;
    cityWarship: ICityWarship | undefined;
    onChangeQty: (qty: number) => void;
}

export const FleetCard = ({ item, cityWarship, onChangeQty }: IProps) => {
    const [qty, setQty] = useState(null);

    const maxShips = cityWarship?.qty || 0;

    return (
        <>
            <Card
                key={item.id}
                imagePath={"warships"}
                object={item}
                qty={maxShips}
            />

            <SInput
                type="number"
                value={qty || ""}
                onChange={(e) => {
                    let number: string | number = e.currentTarget.value;

                    if (!number) {
                        number = 0;
                    }

                    number = parseInt(String(number), 10);
                    console.log(number);
                    if (number > 0) {
                        console.log(maxShips);
                        if (number > maxShips) {
                            number = maxShips;
                        }

                        // @ts-ignore
                        setQty(number);
                    } else {
                        setQty(null);
                    }

                    onChangeQty(number);
                }}
            />
        </>
    );
};

const SInput = styled.input`
    display: inline-block;
    margin-bottom: 10px;
    width: 100%;
`;
