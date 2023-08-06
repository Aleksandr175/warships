import React, { useEffect, useRef, useState, useCallback } from "react";
import { ICityResources } from "../types/types";
import { Icon } from "./Common/Icon";
import styled from "styled-components";

export const CityResources = ({
    gold,
    population,
    productionGold,
}: ICityResources) => {
    const timer = useRef<NodeJS.Timeout | null>(null);
    const [goldValue, setGoldValue] = useState<number>(gold || 0);

    const updateGoldValue = useCallback(() => {
        setGoldValue((oldGoldValue) => {
            const production = (productionGold ?? 0) / 3600;
            return oldGoldValue + production;
        });
    }, [productionGold]);

    useEffect(() => {
        timer.current = setInterval(updateGoldValue, 1000);

        return () => {
            if (timer.current) clearInterval(timer.current);
        };
    }, [updateGoldValue]);

    useEffect(() => {
        setGoldValue(gold || 0);
    }, [gold]);

    return (
        <SResources className="d-flex">
            <SResource>
                <Icon title="gold" />
                {Math.floor(goldValue)}{" "}
                <SProduction>
                    {productionGold ? `+${productionGold}` : ""}
                </SProduction>
            </SResource>
            <SResource>
                <Icon title="worker" />
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

const SProduction = styled.span`
    position: relative;
    display: inline-block;
    top: -5px;
    padding-left: 5px;
    color: green;
    font-size: 12px;
    font-weight: 600;
`;
