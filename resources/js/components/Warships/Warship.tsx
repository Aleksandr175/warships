import React, { useEffect, useRef, useState } from "react";
import { ICityResources, IWarship } from "../../types/types";
import styled from "styled-components";
import { Card } from "../Common/Card";
import { Icon } from "../Common/Icon";

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
            <Card
                object={warship}
                qty={currentQty}
                imagePath={"warships"}
                // TODO: fix it
                timer={0}
            />
            <div className={"row"}>
                <div className={"col-6"}>
                    <Icon title={"gold"} />
                    <SText>{warship.gold}</SText>
                </div>

                <div className={"col-6"}>
                    <Icon title={"worker"} />
                    <SText>{warship.population}</SText>
                </div>

                <div className={"col-6"}>
                    <Icon title={"attack"} />
                    <SText>{warship.attack}</SText>
                </div>

                <div className={"col-6"}>
                    <Icon title={"speed"} />
                    <SText>{warship.speed}</SText>
                </div>

                <div className={"col-6"}>
                    <Icon title={"capacity"} />
                    <SText>{warship.capacity}</SText>
                </div>

                <div className={"col-6"}>
                    <Icon title={"heart"} />
                    <SText>{warship.health}</SText>
                </div>

                <div className={"col-6"}>
                    <Icon title={"time"} />
                    <SText>{warship.time}</SText>
                </div>
            </div>

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

const SInput = styled.input`
    display: inline-block;
    margin-bottom: 10px;
    width: 100%;
`;

const SText = styled.span`
    line-height: 20px;
    display: inline-block;
    vertical-align: middle;
    padding-left: 10px;
    font-size: 14px;
`;
