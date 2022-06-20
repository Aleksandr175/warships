import React, { useEffect, useRef, useState } from "react";
import { ICityResources, IWarship } from "../../types/types";
import styled from "styled-components";

interface IProps {
    warship: IWarship;
    run: (warshipId: number, qty: number) => void;
    getWarships: () => void;
    cityResources: ICityResources;
    currentQty?: number;
}

export const Warship = ({
    warship,
    run,
    getWarships,
    cityResources,
    currentQty,
}: IProps) => {
    const [timeLeft, setTimeLeft] = useState<number | null>(null);
    const timer = useRef();
    const [qty, setQty] = useState(null);

    let maxShips = 0;

    const maxShipsByGold = Math.floor(cityResources.gold / warship.gold);
    const maxShipsByPopulation = Math.floor(
        cityResources.population / warship.population
    );

    maxShips = Math.min(maxShipsByGold, maxShipsByPopulation);

    useEffect(() => {
        // TODO strange decision
        if (timeLeft === -1) {
            clearInterval(timer.current);
            getWarships();
        }
    }, [timeLeft]);

    function handleTimer() {
        setTimeLeft((lastTimeLeft) => {
            // @ts-ignore
            return lastTimeLeft - 1;
        });
    }

    function isWarshipDisabled() {
        return (
            warship.gold > cityResources.gold ||
            warship.population > cityResources.population
        );
    }

    return (
        <div className={"col-sm-6 col-md-4"} key={warship.id}>
            <SBuildingImageWrapper
                style={{
                    backgroundImage: `url("../images/warships/${warship.id}.svg")`,
                }}
            >
                <SBuildingLvlWrapper>
                    <SBuildingLvl>{currentQty}</SBuildingLvl>
                </SBuildingLvlWrapper>
            </SBuildingImageWrapper>
            <h4>{warship.title}</h4>
            <span>{warship.description}</span>

            <p>
                Gold: {warship.gold}. Workers: {warship.population}
                <br />
                Attack: {warship.attack}.
                <br />
                Speed: {warship.speed}
                <br />
                Capacity: {warship.capacity}. <br />
                Health: {warship.health}
                <br />
                Time: {warship.time}
                <br />
            </p>
            <p>You can create: {maxShips}</p>
            <SInput
                type="number"
                value={qty || ""}
                onChange={(e) => {
                    let number: string | number = e.currentTarget.value;

                    if (!number) {
                        number = 0;
                    }

                    number = parseInt(String(number), 10);

                    if (number > 0) {
                        if (number > maxShips) {
                            number = maxShips;
                        }

                        // @ts-ignore
                        setQty(number);
                    } else {
                        setQty(null);
                    }
                }}
            />
            <button
                className={"btn btn-primary"}
                disabled={isWarshipDisabled() || !qty}
                onClick={() => {
                    run(warship.id, qty ? qty : 0);
                    setQty(null);
                }}
            >
                Create
            </button>

            <br />
            <br />
        </div>
    );
};

const SBuildingImageWrapper = styled.div`
    border: 1px solid black;
    height: 100px;
    margin-bottom: 20px;
    position: relative;

    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #ddd;
`;

const SBuildingLvlWrapper = styled.div`
    position: absolute;
    top: 0;
    right: 0;
    border: 30px solid transparent;
    border-top: 30px solid #ccc;
    border-right: 30px solid #ccc;
`;

const SBuildingLvl = styled.span`
    position: absolute;
    top: -25px;
    right: -20px;
    font-size: 16px;
    font-weight: 700;
`;

const SInput = styled.input`
    display: inline-block;
    margin-bottom: 10px;
    width: 100%;
`;
