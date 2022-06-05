import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import { Overview } from "./Overview";
import { Buildings } from "./Buildings/Buildings";
import { Researches } from "./Researches/Researches";
import { httpClient } from "../httpClient/httpClient";
import styled from "styled-components";
import {
    ICity,
    IDictionary,
    ICityResources,
    ICityBuilding,
    ICityBuildingQueue,
    ICityResearchQueue,
} from "../types/types";
import { CityResources } from "./CityResources";

const App = () => {
    const [userInfo, setUserInfo] = useState();
    const [city, setCity] = useState<ICity>();
    const [cities, setCities] = useState<ICity[]>();
    const [cityResources, setCityResources] = useState<ICityResources>();
    const [isLoading, setIsLoading] = useState(true);
    const [dictionaries, setDictionaries] = useState<IDictionary>();
    const [buildings, setBuildings] = useState<ICityBuilding[] | undefined>();
    const [queue, setQueue] = useState<ICityBuildingQueue>();
    const [queueResearch, setQueueResearch] = useState<ICityResearchQueue>();

    useEffect(() => {
        httpClient.get("/user").then((response) => {
            httpClient.get("/dictionaries").then((respDictionary) => {
                setUserInfo(response.data.data);
                setCity(response.data.data.cities[0]);
                setCities(response.data.data.cities);
                setDictionaries(respDictionary.data);

                setIsLoading(false);
            });
        });
    }, []);

    useEffect(() => {
        getCityResources();
    }, [city]);

    useEffect(() => {
        getBuildings();
        getResearches();
    }, [city]);

    function getCityResources() {
        if (!city) return;

        httpClient.get("/city/" + city.id).then((response) => {
            setCityResources(response.data.data);
        });
    }

    function updateCityResources(cityResources: ICityResources) {
        const tempCity = Object.assign({}, city);

        tempCity.gold = cityResources.gold || 0;
        tempCity.population = cityResources.population || 0;

        setCity(tempCity);
    }

    function getBuildings() {
        if (!city?.id) {
            return;
        }

        httpClient.get("/buildings?cityId=" + city?.id).then((response) => {
            setBuildings(response.data.buildings);
            setQueue(response.data.buildingQueue);
        });

        getCityResources();
    }

    function getResearches() {
        httpClient.get("/researches").then((response) => {
            //setBuildings(response.data.buildings);
            setQueueResearch(response.data.queue);
        });
    }

    function getProductionGold() {
        if (buildings) {
            // TODO change 2
            const miner = buildings.find((building) => {
                return (
                    building.buildingId === 2 && building.cityId === city?.id
                );
            });

            if (miner) {
                const lvl = miner.lvl;

                const production = dictionaries?.buildingsProduction?.find(
                    (bp) =>
                        bp.buildingId === miner.buildingId &&
                        bp.lvl === lvl &&
                        bp.resource === "gold"
                );

                return production?.qty;
            }
        }

        return 0;
    }

    function selectCity(c: ICity) {
        setCity(c);
    }

    if (isLoading) {
        return <></>;
    }

    return (
        <Router>
            <SHeader className="container">
                <div className={"row"}>
                    <div className={"col-12"}>
                        {city && cities && (
                            <SResourcesPanel>
                                <div>
                                    Islands:
                                    {cities.map((c) => {
                                        return (
                                            <SCity
                                                key={c.id}
                                                active={c.id === city.id}
                                                onClick={() => selectCity(c)}
                                            >
                                                {c.title}
                                            </SCity>
                                        );
                                    })}
                                </div>
                                <SResources>
                                    <li>
                                        Coords: {city.coordX}:{city.coordY}
                                    </li>
                                    <CityResources
                                        gold={cityResources?.gold || 0}
                                        population={
                                            cityResources?.population || 0
                                        }
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
                            <Link to={"/dashboard"}>Overview</Link>
                            <br />
                            <Link to={"/buildings"}>Buildings</Link>
                            <br />
                            <Link to={"/researches"}>Researches</Link>
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
                                            getBuildings={getBuildings}
                                            buildings={buildings}
                                            setBuildings={setBuildings}
                                            buildingsProduction={
                                                dictionaries.buildingsProduction
                                            }
                                            setQueue={setQueue}
                                            queue={queue}
                                        />
                                    }
                                />
                                <Route
                                    path={"researches"}
                                    element={
                                        <Researches
                                            cityId={city.id}
                                            dictionary={dictionaries.researches}
                                            resourcesDictionary={
                                                dictionaries.researchResources
                                            }
                                            updateCityResources={
                                                updateCityResources
                                            }
                                            cityResources={{
                                                gold: city.gold,
                                                population: city.population,
                                            }}
                                            researches={
                                                dictionaries.userResearches
                                            }
                                            /*setBuildings={setBuildings}*/
                                            setQueue={setQueueResearch}
                                            queue={queueResearch}
                                        />
                                    }
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

const SCity = styled.span<{ active?: boolean }>`
    cursor: pointer;
    display: inline-block;
    margin-left: 10px;
    text-decoration: underline;

    ${(props) =>
        props.active ? "text-decoration: none; font-weight: 700;" : ""}
`;
