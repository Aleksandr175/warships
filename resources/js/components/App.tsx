import React from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  NavLink,
} from "react-router-dom";
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
import { Logs } from "./Logs/Logs";
import { Log } from "./Logs/Log";
import { Icon } from "./Common/Icon";

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
    getResearches,
    fleets,
    fleetDetails,
    fleetCitiesDictionary,
    getProductionGold,
    dictionaries,
    userId,
  } = useAppLogic();

  if (isLoading) {
    return <></>;
  }

  return (
    <Router>
      <SAppContainer className={"container"}>
        <div className={"row"}>
          <div className="col-2">
            <SColumn>
              <NavLink
                to={"/dashboard"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Overview
              </NavLink>
              <NavLink
                to={"/buildings"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Buildings
              </NavLink>
              <NavLink
                to={"/researches"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Researches
              </NavLink>
              <NavLink
                to={"/warships"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Warships
              </NavLink>
              <NavLink
                to={"/fleets"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Fleets
              </NavLink>
              <NavLink
                to={"/map"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Map
              </NavLink>
              <NavLink
                to={"/logs"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Battle Logs
              </NavLink>
            </SColumn>
          </div>
          <div className={"col-7"}>
            {city && cities && (
              <div className={"row"}>
                <div className={"col-5"}>
                  <SColumn>
                    <CityResources
                      gold={cityResources?.gold || 0}
                      population={cityResources?.population || 0}
                      productionGold={getProductionGold()}
                    />
                  </SColumn>
                </div>
                <div className={"col-5"}>
                  <SColumn>
                    <SIsland>
                      <Icon title={"island"} />[{city.coordX}:{city.coordY}]
                    </SIsland>
                    {/*Islands:
                                        {cities.map((c) => {
                                            return (
                                                <SCity
                                                    key={c.id}
                                                    active={c.id === city.id}
                                                    onClick={() =>
                                                        selectCity(c)
                                                    }
                                                >
                                                    {c.title}
                                                </SCity>
                                            );
                                        })}*/}
                  </SColumn>
                </div>
                <div className={"col-2 text-center"}>
                  <SColumn>
                    <Icon title={"messages"} />
                  </SColumn>
                </div>
              </div>
            )}

            <SColumn>
              {city && dictionaries && cities && (
                <Routes>
                  <Route path={"dashboard"} element={<Overview />} />
                  <Route
                    path={"buildings"}
                    element={
                      <Buildings
                        cityId={city.id}
                        buildingsDictionary={dictionaries.buildings}
                        buildingResourcesDictionary={
                          dictionaries.buildingResources
                        }
                        updateCityResources={updateCityResources}
                        cityResources={{
                          gold: city.gold,
                          population: city.population,
                        }}
                        getBuildings={getBuildings}
                        buildings={buildings}
                        setBuildings={setBuildings}
                        buildingsProduction={dictionaries.buildingsProduction}
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
                        researchResourcesDictionary={
                          dictionaries.researchResources
                        }
                        updateCityResources={updateCityResources}
                        cityResources={{
                          gold: city.gold,
                          population: city.population,
                        }}
                        researches={dictionaries.userResearches}
                        /*setBuildings={setBuildings}*/
                        setQueue={setQueueResearch}
                        queue={queueResearch}
                        getResearches={getResearches}
                      />
                    }
                  />
                  <Route
                    path={"warships"}
                    element={
                      <Warships
                        cityId={city.id}
                        dictionary={dictionaries.warships}
                        resourcesDictionary={dictionaries.warshipsResources}
                        updateCityResources={updateCityResources}
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
                  <Route path={"map"} element={<Map cityId={city.id} />} />
                  <Route
                    path="logs/:id"
                    element={
                      <Log
                        dictionary={dictionaries.warships}
                        userId={userId || 0}
                      />
                    }
                  />
                  <Route
                    path={"logs"}
                    element={
                      <Logs
                        dictionary={dictionaries.warships}
                        userId={userId || 0}
                      />
                    }
                  />
                </Routes>
              )}
            </SColumn>
          </div>
          <div className={"col-3"}>
            <SColumn>
              {dictionaries &&
                fleetCitiesDictionary &&
                fleets &&
                fleetDetails &&
                fleets.length > 0 && (
                  <Fleets
                    fleets={fleets}
                    fleetDetails={fleetDetails}
                    dictionaries={dictionaries}
                    fleetCitiesDictionary={fleetCitiesDictionary}
                  />
                )}
            </SColumn>
          </div>
        </div>
      </SAppContainer>
    </Router>
  );
};

export default App;

const SAppContainer = styled.div`
  padding-top: var(--block-gutter-y);
`;

const SColumn = styled.div`
  background: var(--background-color);
  padding: var(--block-padding);
  border-radius: var(--block-border-radius);
  margin-bottom: var(--block-gutter-y);
`;

const SCity = styled.span<{ active?: boolean }>`
  cursor: pointer;
  display: inline-block;
  margin-left: 10px;
  text-decoration: underline;

  ${(props) => (props.active ? "text-decoration: none; font-weight: 700;" : "")}
`;

const SIsland = styled.div`
  > i {
    padding-right: 5px;
  }
`;
