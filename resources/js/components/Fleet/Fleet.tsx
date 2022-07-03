import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled from "styled-components";
import { ICityWarship, IMapCity, IWarship } from "../../types/types";
import { Warship } from "../Warships/Warship";
import { Card } from "../Common/Card";
import { FleetCard } from "./FleetCard";

interface IProps {
    cityId: number;
    dictionary: IWarship[];
    warships: ICityWarship[] | undefined;
}

type TTask = "attack" | "move" | "trade" | "transport";

interface IFleetDetail {
    warshipId: number;
    qty: number;
}

interface IFleet {
    coordX: number;
    coordY: number;
    fleetDetails: IFleetDetail[];
    recursive?: 1 | 0;
    taskType: TTask;
}

export const Fleet = ({ cityId, dictionary, warships }: IProps) => {
    const [taskType, setTaskType] = useState<TTask>("trade");
    const [coordX, setCoordX] = useState<number | string>("");
    const [coordY, setCoordY] = useState<number | string>("");

    const [fleet, setFleet] = useState<IFleet>({} as IFleet);

    useEffect(() => {
        const details = [] as IFleetDetail[];
        dictionary?.forEach((warship) => {
            details.push({
                warshipId: warship.id,
                qty: 0,
            });
        });

        setFleet({
            coordX: 0,
            coordY: 0,
            fleetDetails: details,
            recursive: 0,
            taskType: "trade",
        });
    }, [warships]);

    useEffect(() => {
        let tempFleet = { ...fleet };

        tempFleet.taskType = taskType;

        setFleet(tempFleet);
    }, [taskType]);

    useEffect(() => {
        let tempFleet = { ...fleet };

        tempFleet.coordX = Number(coordX);

        setFleet(tempFleet);
    }, [coordX]);

    useEffect(() => {
        let tempFleet = { ...fleet };

        tempFleet.coordY = Number(coordY);

        setFleet(tempFleet);
    }, [coordY]);

    const [recursive, setRecursive] = useState(false);

    function getCityWarship(warshipId: number): ICityWarship | undefined {
        return warships?.find((warship) => warship.warshipId === warshipId);
    }

    const onChangeQty = (warshipId: number, qty: number) => {
        let tempFleet = { ...fleet };
        console.log(warshipId, qty);
        tempFleet.fleetDetails = tempFleet.fleetDetails.map((detail) => {
            if (detail.warshipId === warshipId) {
                detail.qty = qty;
            }

            return detail;
        });

        setFleet(tempFleet);
    };

    console.log(fleet);

    return (
        <div className={"row"}>
            {dictionary.map((item) => {
                return (
                    <div className={"col-sm-6 col-md-4"} key={item.id}>
                        <FleetCard
                            cityId={cityId}
                            item={item}
                            cityWarship={getCityWarship(item.id)}
                            onChangeQty={(qty) => onChangeQty(item.id, qty)}
                        />
                    </div>
                );
            })}

            <div className={"col-12"}>
                <div>
                    Coord X:{" "}
                    <SInput
                        type="number"
                        value={coordX || ""}
                        onChange={(e) => {
                            setCoordX(Number(e.currentTarget.value));
                        }}
                    />
                </div>
                <div>
                    Coord Y:{" "}
                    <SInput
                        type="number"
                        value={coordY || ""}
                        onChange={(e) => {
                            setCoordY(Number(e.currentTarget.value));
                        }}
                    />
                </div>
            </div>
            <div className={"col-12"}>
                Task:
                <STaskType
                    selected={taskType === "attack" ? 1 : 0}
                    onClick={() => setTaskType("attack")}
                >
                    Attack{" "}
                </STaskType>
                <STaskType
                    selected={taskType === "trade" ? 1 : 0}
                    onClick={() => setTaskType("trade")}
                >
                    Trade{" "}
                </STaskType>
                <STaskType
                    selected={taskType === "transport" ? 1 : 0}
                    onClick={() => setTaskType("transport")}
                >
                    Transport{" "}
                </STaskType>
                <STaskType
                    selected={taskType === "move" ? 1 : 0}
                    onClick={() => setTaskType("move")}
                >
                    Move{" "}
                </STaskType>
            </div>

            <div className={"col-12"}>
                <span>Recursive:</span>
                <input
                    type={"checkbox"}
                    value={Number(recursive)}
                    onChange={(e) => {
                        const tempFleet = { ...fleet };

                        tempFleet.recursive = Number(e.currentTarget.checked)
                            ? 1
                            : 0;

                        setFleet(tempFleet);
                    }}
                />
            </div>

            <div className={"col-12"}>
                <button
                    className={"btn btn-primary"}
                    disabled={!taskType}
                    onClick={() => {
                        console.log("send ships");
                    }}
                >
                    Send
                </button>
            </div>
        </div>
    );
};

const STaskType = styled.span<{ selected?: number }>`
    cursor: pointer;
    display: inline-block;
    margin-left: 10px;

    ${(props) => (props.selected ? "text-decoration: underline" : "")};
`;

const SInput = styled.input`
    display: inline-block;
    margin-bottom: 10px;
    width: 100%;
`;
