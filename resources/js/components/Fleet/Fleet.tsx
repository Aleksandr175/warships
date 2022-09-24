import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled, { css } from "styled-components";
import {
    ICity,
    ICityWarship,
    IFleet,
    IFleetDetail,
    IWarship,
    TTask,
} from "../../types/types";
import { FleetCard } from "./FleetCard";
import { InputNumber } from "../Common/InputNumber";

interface IProps {
    dictionary: IWarship[];
    warships: ICityWarship[] | undefined;
    cities: ICity[];
    city: ICity;
}

export const Fleet = ({ dictionary, warships, cities, city }: IProps) => {
    const [taskType, setTaskType] = useState<TTask>("trade");
    const [coordX, setCoordX] = useState<number>(0);
    const [coordY, setCoordY] = useState<number>(0);
    const [actualCityWarships, setActualCityWarships] = useState(warships);
    const [gold, setGold] = useState(0);

    const [renderKey, setRenderKey] = useState(0);

    const [fleet, setFleet] = useState<IFleet>({} as IFleet);
    const [fleetDetails, setFleetDetails] = useState<IFleetDetail[]>(
        [] as IFleetDetail[]
    );

    useEffect(() => {
        setActualCityWarships(warships);
    }, [warships]);

    // TODO: add init func for fleet details, we have some problem here
    // set default values
    // update details with dependencies in diff func
    useEffect(() => {
        const details = [] as IFleetDetail[];
        dictionary?.forEach((warship) => {
            details.push({
                warshipId: warship.id,
                qty: 0,
            });
        });

        setFleetDetails(details);

        setFleet({
            coordX: 0,
            coordY: 0,
            repeating: 0,
            taskType: "trade",
            cityId: city.id,
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

    const [repeating, setRepeating] = useState(false);

    function getCityWarship(warshipId: number): ICityWarship | undefined {
        return actualCityWarships?.find(
            (warship) => warship.warshipId === warshipId
        );
    }

    console.log("fleetDetails", fleetDetails);

    const onChangeQty = (warshipId: number, qty: number) => {
        setFleetDetails((oldFleetDetails) => {
            let tempFleetDetails = [...oldFleetDetails];

            tempFleetDetails =
                tempFleetDetails?.map((detail) => {
                    if (detail.warshipId === warshipId) {
                        detail.qty = qty;
                    }

                    return detail;
                }) || [];

            return tempFleetDetails;
        });
    };

    const sendFleet = (): void => {
        httpClient
            .post("/fleets/send", { ...fleet, fleetDetails, gold })
            .then((response) => {
                console.log(response);

                setActualCityWarships(response.data.warships);
                alert("Fleet has been sent");
                setRenderKey(renderKey + 1);
            });
    };

    const chooseCity = (city: ICity): void => {
        setFleet((prevFleet) => {
            return {
                ...prevFleet,
                coordX: city.coordX,
                coordY: city.coordY,
            };
        });
        setCoordX(city.coordX);
        setCoordY(city.coordY);
    };

    const getMaxCapacity = (): number => {
        let maxCapacity = 0;

        fleetDetails?.forEach((fDetail) => {
            const dictWarship = dictionary.find(
                (warship) => warship.id === fDetail.warshipId
            );

            if (dictWarship) {
                maxCapacity += dictWarship.capacity * fDetail.qty;
            }
        });

        return maxCapacity;
    };

    const maxCapacity = getMaxCapacity();

    if (gold > maxCapacity) {
        setGold(maxCapacity);
    }

    return (
        <div className={"row"}>
            {dictionary.map((item) => {
                return (
                    <div className={"col-sm-6 col-md-2"} key={item.id}>
                        <FleetCard
                            key={renderKey}
                            cityId={city.id}
                            item={item}
                            cityWarship={getCityWarship(item.id)}
                            onChangeQty={(qty) => onChangeQty(item.id, qty)}
                        />
                    </div>
                );
            })}

            <div className={"col-12"}>
                <br />
            </div>

            <div className={"col-12"}>
                <div>
                    <strong>Target Island:</strong>
                </div>
                <div>
                    {cities.map((city) => {
                        return (
                            <SCityPreset
                                active={
                                    city.coordX === coordX &&
                                    city.coordY === coordY
                                }
                                onClick={() => chooseCity(city)}
                            >
                                {city.title}
                            </SCityPreset>
                        );
                    })}
                </div>
                <SCoordinatesBlock className={"row"}>
                    <div className={"col-2"}>
                        Coord X:{" "}
                        <InputNumber
                            value={coordX}
                            onChange={(value) => setCoordX(value)}
                            maxNumber={100}
                        />
                    </div>
                    <div className={"col-2"}>
                        Coord Y:{" "}
                        <InputNumber
                            value={coordY}
                            onChange={(value) => setCoordY(value)}
                            maxNumber={100}
                        />
                    </div>
                </SCoordinatesBlock>
            </div>
            <div className={"col-4"}>
                Gold (Capacity: {maxCapacity}):
                <InputNumber
                    value={gold}
                    onChange={(value) => setGold(value)}
                    maxNumber={
                        city.gold > maxCapacity ? maxCapacity : city.gold
                    }
                />
            </div>

            <div className={"col-12"}>
                <br />
            </div>

            <div className={"col-12"}>
                Task:
                {/* TODO use dictionary for Task Types */}
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
                <br />
                <br />
            </div>

            <div className={"col-12"}>
                <span>Repeating: </span>
                <input
                    type={"checkbox"}
                    value={Number(repeating)}
                    onChange={(e) => {
                        const tempFleet = { ...fleet };

                        tempFleet.repeating = Number(e.currentTarget.checked)
                            ? 1
                            : 0;

                        setFleet(tempFleet);
                    }}
                />
            </div>

            <div className={"col-12"}>
                <br />
                <button
                    className={"btn btn-primary"}
                    disabled={!taskType || !coordY || !coordX}
                    onClick={() => {
                        console.log(fleet);
                        console.log("send ships");
                        sendFleet();
                    }}
                >
                    Send Fleet
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

const SCityPreset = styled.div<{ active?: boolean }>`
    cursor: pointer;
    display: inline-block;
    padding: 5px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;

    ${({ active }) =>
        active
            ? css`
                  background-color: #0000ff33;
              `
            : ""};
`;

const SCoordinatesBlock = styled.div`
    margin-top: 20px;
    margin-bottom: 20px;
`;

const SGoldInputWrapper = styled.div`
    display: flex;
`;
