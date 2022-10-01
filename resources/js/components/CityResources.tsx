import React, { useEffect, useRef, useState } from "react";
import { ICityResources } from "../types/types";
import { Icon } from "./Common/Icon";

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
                <Icon title={"gold"} />
                {Math.floor(goldValue)}{" "}
                {productionGold ? `(+${productionGold})` : ""}
            </li>
            <li>
                <Icon title={"worker"} />
                {population}
            </li>
        </>
    );
};
