import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import { Overview } from "./Overview";
import { Buildings } from "./Buildings";
import { Researches } from "./Researches";
import { httpClient } from "../httpClient/httpClient";
import styled from "styled-components";
import { ICity, IDictionary, ICityResources } from "../types/types";
import { CityResources } from "./CityResources";

const App = () => {
    const [userInfo, setUserInfo] = useState();
    const [city, setCity] = useState<ICity>();
    const [isLoading, setIsLoading] = useState(true);
    const [dictionaries, setDictionaries] = useState<IDictionary>();

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

    function updateCityResources(cityResources: ICityResources) {
        const tempCity = Object.assign({}, city);

        tempCity.gold = cityResources.gold;
        tempCity.population = cityResources.population;

        setCity(tempCity);
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
