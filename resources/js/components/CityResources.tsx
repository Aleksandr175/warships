import React from "react";
import { ICityResources } from "../types/types";

export const CityResources = ({
    gold,
    population,
    productionGold,
}: ICityResources) => {
    return (
        <>
            <li>
                Золото: {gold} {productionGold ? `(+${productionGold})` : ""}
            </li>
            <li>Население: {population}</li>
        </>
    );
};
