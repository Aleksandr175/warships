import React, { useEffect, useRef, useState } from "react";
import { ICityResources } from "../types/types";

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
        <>
            <li>
                Gold: {Math.floor(goldValue)}{" "}
                {productionGold ? `(+${productionGold})` : ""}
            </li>
            <li>Workers: {population}</li>
        </>
    );
};
