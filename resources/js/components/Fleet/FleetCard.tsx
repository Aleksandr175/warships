import React, { useState } from "react";
import { useEffect } from "react";
import styled from "styled-components";
import { ICityWarship, IWarship } from "../../types/types";
import { Card } from "../Common/Card";
import { InputNumber } from "../Common/InputNumber";

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
        <>
            <Card
                key={item.id}
                imagePath={"warships"}
                object={item}
                qty={maxShips}
                // TODO: fix it
                timer={0}
            />

            <SInputWrapper>
                <InputNumber
                    value={qty}
                    onChange={(value) => setQty(value)}
                    maxNumber={maxShips}
                    disabled={!maxShips}
                />
            </SInputWrapper>
        </>
    );
};

const SInputWrapper = styled.div`
    margin-bottom: 10px;
`;
