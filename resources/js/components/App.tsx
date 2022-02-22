import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import { Overview } from "./Overview";
import { Buildings } from "./Buildings";
import { Researches } from "./Researches";
import { httpClient } from "../httpClient/httpClient";
import styled from "styled-components";
import {
    ICity,
    IDictionary,
    ICityResources,
    ICityBuilding,
    IBuildingsProduction,
    ICityBuildingQueue,
} from "../types/types";
import { CityResources } from "./CityResources";

const App = () => {
    const [userInfo, setUserInfo] = useState();
    const [city, setCity] = useState<ICity>();
    const [isLoading, setIsLoading] = useState(true);
    const [dictionaries, setDictionaries] = useState<IDictionary>();
    const [buildings, setBuildings] = useState<ICityBuilding[]>();
    const [buildingsProduction, setBuildingsProduction] =
        useState<IBuildingsProduction[]>();
    const [queue, setQueue] = useState<ICityBuildingQueue>();

    useEffect(() => {
        httpClient.get("/user").then((response) => {
            httpClient.get("/dictionaries").then((respDictionary) => {
                setUserInfo(response.data.data);
                setCity(response.data.data.cities[0]);
                setDictionaries(respDictionary.data);

                setIsLoading(false);
            });
        });
    }, []);

    useEffect(() => {
        getBuildings();
    }, [city]);

    function updateCityResources(cityResources: ICityResources) {
        const tempCity = Object.assign({}, city);

        tempCity.gold = cityResources.gold;
        tempCity.population = cityResources.population;

        setCity(tempCity);
    }

    function getBuildings() {
        if (!city?.id) {
            return;
        }

        httpClient.get("/buildings?cityId=" + city?.id).then((response) => {
            setBuildings(response.data.buildings);
            setBuildingsProduction(response.data.buildingsProduction);
        });
    }

    function getProductionGold() {
        if (buildings) {
            // TODO change 2
            const miner = buildings.find((building) => {
                return building.id === 2 && building.cityId === city?.id;
            });

            if (miner) {
                const lvl = miner.lvl;

                const production = buildingsProduction?.find(
                    (bp) =>
                        bp.buildingId === miner.id &&
                        bp.lvl === lvl &&
                        bp.resource === "gold"
                );

                return production?.qty;
            }
        }

        return 0;
    }

    if (isLoading) {
        return <></>;
    }

    return (
        <Router>
            <SHeader className="container">
                <div className={"row"}>
                    <div className={"col-9 offset-3"}>
                        {city && (
                            <SResourcesPanel>
                                <div>Выбранный остров: {city.title}</div>
                                <SResources>
                                    <li>
                                        Координаты: {city.coordX}:{city.coordY}
                                    </li>
                                    <CityResources
                                        gold={city.gold}
                                        population={city.population}
                                        productionGold={getProductionGold()}
                                    />
                                </SResources>
                            </SResourcesPanel>
                        )}
                    </div>
                </div>
            </SHeader>

            <div className={"container"}>
                <div className={"row"}>
                    <div className={"col-3 d-flex align-items-stretch"}>
                        <SColumnMenu>
                            <Link to={"/dashboard"}>Обзор</Link>
                            <br />
                            <Link to={"/buildings"}>Постройки</Link>
                            <br />
                            <Link to={"/researches"}>Исследования</Link>
                        </SColumnMenu>
                    </div>
                    <SColumnContent className={"col-9"}>
                        {city && dictionaries && (
                            <Routes>
                                <Route
                                    path={"dashboard"}
                                    element={<Overview />}
                                />
                                <Route
                                    path={"buildings"}
                                    element={
                                        <Buildings
                                            cityId={city.id}
                                            buildingsDictionary={
                                                dictionaries.buildings
                                            }
                                            buildingResourcesDictionary={
                                                dictionaries.buildingResources
                                            }
                                            updateCityResources={
                                                updateCityResources
                                            }
                                            cityResources={{
                                                gold: city.gold,
                                                population: city.population,
                                            }}
                                        />
                                    }
                                />
                                <Route
                                    path={"researches"}
                                    element={<Researches />}
                                />
                            </Routes>
                        )}
                    </SColumnContent>
                </div>
            </div>
        </Router>
    );
};

export default App;

const SResourcesPanel = styled.div`
    text-align: right;
`;

const SResources = styled.div`
    li {
        display: inline-block;
        padding-left: 20px;
    }
`;

const SHeader = styled.div`
    background: white;
    padding: 10px 20px;
    margin-top: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
`;

const SColumnMenu = styled.div`
    width: 100%;
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-right: 20px;
    min-height: 200px;
`;
const SColumnContent = styled.div`
    background: white;
    border-radius: 10px;
    padding: 20px;
`;
