import React, { useEffect, useRef, useState } from "react";
import { ICityResources } from "../types/types";
import { Icon } from "./Common/Icon";
import styled from "styled-components";

export const CityResources = ({
    gold,
    population,
    productionGold,
}: ICityResources) => {
    const timer = useRef();
    const productionGoldRef = useRef(0);
    productionGoldRef.current = productionGold ? productionGold : 0;

    const [goldValue, setGoldValue] = useState(gold || 0);

    useEffect(() => {
        // @ts-ignore
        timer.current = setInterval(handleTimer, 1000);

        return () => {
            clearInterval(timer.current);
        };
    }, []);

    useEffect(() => {
        setGoldValue(gold || 0);
    }, [gold]);

    const handleTimer = () => {
        setGoldValue((oldGoldValue) => {
            const production = productionGoldRef.current
                ? productionGoldRef.current / 3600
                : 0;

            return oldGoldValue + production;
        });
    };

    return (
        <SResources className={"d-flex"}>
            <SResource>
                <Icon title={"gold"} />
                {Math.floor(goldValue)}{" "}
                <SProducation>
                    {productionGold ? `+${productionGold}` : ""}
                </SProducation>
            </SResource>
            <SResource>
                <Icon title={"worker"} />
                {population}
            </SResource>
        </SResources>
    );
};

const SResources = styled.div`
    display: flex;
    align-items: center;
    gap: 20px;
`;
const SResource = styled.div`
    position: relative;
    display: flex;
    align-items: center;
`;
const SProducation = styled.span`
    position: relative;
    display: inline-block;
    top: -5px;
    padding-left: 5px;
    color: green;
    font-size: 12px;
    font-weight: 600;
`;
