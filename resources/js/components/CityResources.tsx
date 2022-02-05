import React from "react";
import { ICityResources } from "../types/types";

export const CityResources = ({ gold, population }: ICityResources) => {
    return (
        <>
            <li>Золото: {gold}</li>
            <li>Население: {population}</li>
        </>
    );
};
