import React from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import { Overview } from "./Overview";
import { Buildings } from "./Buildings/Buildings";
import { Researches } from "./Researches/Researches";
import styled from "styled-components";
import { CityResources } from "./CityResources";
import { Warships } from "./Warships/Warships";
import { Map } from "./Map/Map";
import { Fleet } from "./Fleet/Fleet";
import { useAppLogic } from "./hooks/useAppLogic";
import { Fleets } from "./Fleets";

const App = () => {
    const {
        isLoading,
        city,
        cityResources,
        selectCity,
        setQueueWarship,
        setQueue,
        setWarships,
        queue,
        getWarships,
        warships,
        queueWarship,
        updateCityResources,
        cities,
        setQueueResearch,
        queueResearch,
        buildings,
        setBuildings,
        getBuildings,
        fleets,
        fleetDetails,
        fleetCitiesDictionary,
        getProductionGold,
        dictionaries,
    } = useAppLogic();

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

            {dictionaries &&
                fleetCitiesDictionary &&
                fleets &&
                fleetDetails &&
                fleets.length > 0 && (
                    <div className={"container"}>
                        <div className={"row"}>
                            <Fleets
                                fleets={fleets}
                                fleetDetails={fleetDetails}
                                dictionaries={dictionaries}
                                fleetCitiesDictionary={fleetCitiesDictionary}
                            />
                        </div>
                    </div>
                )}

            <div className={"container"}>
                <div className={"row"}>
                    <div
                        className={"col-3 d-flex align-items-stretch"}
                        style={{ paddingLeft: "0" }}
                    >
                        <SColumnMenu>
                            <Link to={"/dashboard"}>Overview</Link>
                            <br />
                            <Link to={"/buildings"}>Buildings</Link>
                            <br />
                            <Link to={"/researches"}>Researches</Link>
                            <br />
                            <Link to={"/warships"}>Warships</Link>
                            <br />
                            <Link to={"/fleets"}>Fleets</Link>
                            <br />
                            <Link to={"/map"}>Map</Link>
                        </SColumnMenu>
                    </div>
                    <SColumnContent className={"col-9"}>
                        {city && dictionaries && cities && (
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
                                <Route
                                    path={"warships"}
                                    element={
                                        <Warships
                                            cityId={city.id}
                                            dictionary={dictionaries.warships}
                                            resourcesDictionary={
                                                dictionaries.warshipsResources
                                            }
                                            updateCityResources={
                                                updateCityResources
                                            }
                                            cityResources={{
                                                gold: city.gold,
                                                population: city.population,
                                            }}
                                            getWarships={getWarships}
                                            warships={warships}
                                            setWarships={setWarships}
                                            setQueue={setQueueWarship}
                                            queue={queueWarship}
                                        />
                                    }
                                />
                                <Route
                                    path={"fleets"}
                                    element={
                                        <Fleet
                                            warships={warships}
                                            dictionary={dictionaries.warships}
                                            cities={cities}
                                            city={city}
                                        />
                                    }
                                />
                                <Route
                                    path={"map"}
                                    element={<Map cityId={city.id} />}
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
    margin-bottom: 20px;
`;

const SColumnMenu = styled.div`
    width: 100%;
    background: white;
    padding: 20px;
    margin-right: 20px;
    min-height: 200px;
`;
const SColumnContent = styled.div`
    background: white;
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
