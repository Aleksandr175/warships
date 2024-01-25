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
import { SAppContainer, SColumn } from "./styles";
import { Messages } from "./Messages/Messages";
import { Message } from "./Messages/Message";

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
    fleetsIncoming,
    getProductionGold,
    dictionaries,
    userId,
    logout,
    unreadMessagesNumber,
    resourcesDictionary,
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
              <SSeparator />
              <NavLink className={"link"} to={"/"} onClick={logout}>
                Logout
              </NavLink>
            </SColumn>
          </div>
          <div className={"col-7"}>
            {city && cities && (
              <div className={"row"}>
                <div className={"col-5"}>
                  <SColumn>
                    {resourcesDictionary && (
                      <CityResources
                        cityResources={cityResources!}
                        resourcesDictionary={resourcesDictionary}
                        gold={0}
                        population={0}
                        /*gold={cityResources?.gold || 0}
                      population={cityResources?.population || 0}*/
                        productionGold={getProductionGold()}
                      />
                    )}
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
                    <SMessagesWrapper>
                      <NavLink to={"/messages"}>
                        <Icon title={"messages"} />
                        {unreadMessagesNumber > 0 && (
                          <SMessagesNumber>
                            {unreadMessagesNumber > 9
                              ? `9+`
                              : unreadMessagesNumber}
                          </SMessagesNumber>
                        )}
                      </NavLink>
                    </SMessagesWrapper>
                  </SColumn>
                </div>
              </div>
            )}

            <div>
              {city && dictionaries && cities && (
                <Routes>
                  <Route path={"dashboard"} element={<Overview />} />
                  <Route
                    path={"buildings"}
                    element={
                      <Buildings
                        cityId={city.id}
                        buildingsDictionary={dictionaries.buildings}
                        buildingDependencyDictionary={
                          dictionaries.buildingDependencies
                        }
                        buildingResourcesDictionary={
                          dictionaries.buildingResources
                        }
                        updateCityResources={updateCityResources}
                        cityResources={city.resources}
                        getBuildings={getBuildings}
                        buildings={buildings}
                        setBuildings={setBuildings}
                        buildingsProduction={dictionaries.buildingsProduction}
                        setQueue={setQueue}
                        queue={queue}
                        researchesDictionary={dictionaries.researches}
                        researches={dictionaries.userResearches}
                        resourcesDictionary={resourcesDictionary!}
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
                        setQueue={setQueueResearch}
                        queue={queueResearch}
                        getResearches={getResearches}
                        researchDependencyDictionary={
                          dictionaries.researchDependencies
                        }
                        buildingsDictionary={dictionaries.buildings}
                      />
                    }
                  />
                  <Route
                    path={"warships"}
                    element={
                      <Warships
                        cityId={city.id}
                        dictionary={dictionaries.warshipsDictionary}
                        updateCityResources={updateCityResources}
                        cityResources={city.resources}
                        getWarships={getWarships}
                        warships={warships}
                        setWarships={setWarships}
                        setQueue={setQueueWarship}
                        queue={queueWarship}
                        warshipDependencies={dictionaries.warshipDependencies}
                        researches={dictionaries.userResearches}
                        researchesDictionary={dictionaries.researches}
                        buildings={buildings!}
                        buildingsDictionary={dictionaries.buildings}
                        resourcesDictionary={resourcesDictionary!}
                      />
                    }
                  />
                  <Route
                    path={"fleets"}
                    element={
                      <Fleet
                        warships={warships}
                        dictionary={dictionaries.warshipsDictionary}
                        cities={cities}
                        city={city}
                      />
                    }
                  />
                  <Route path={"map"} element={<Map fleets={fleets} />} />
                  <Route
                    path="logs/:id"
                    element={
                      <Log
                        dictionary={dictionaries.warshipsDictionary}
                        userId={userId || 0}
                      />
                    }
                  />
                  <Route
                    path={"logs"}
                    element={
                      <Logs
                        dictionary={dictionaries.warshipsDictionary}
                        userId={userId || 0}
                      />
                    }
                  />
                  <Route path={"messages"} element={<Messages />} />
                  <Route path={"messages/:id"} element={<Message />} />
                </Routes>
              )}
            </div>
          </div>
          <div className={"col-3"}>
            <SColumn>
              {dictionaries && fleetCitiesDictionary && (
                <Fleets
                  fleets={fleets}
                  fleetsIncoming={fleetsIncoming}
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

const SIsland = styled.div`
  > i {
    padding-right: 5px;
  }
`;

const SSeparator = styled.div`
  height: 1px;
  background: black;
  opacity: 0.2;
  margin: 10px 10px;
`;

const SMessagesWrapper = styled.div`
  position: relative;
  cursor: pointer;
`;

const SMessagesNumber = styled.div`
  border-radius: 50%;
  background-color: #6f4ca4;
  font-size: 12px;
  width: 24px;
  height: 24px;
  color: white;
  position: absolute;
  top: -7px;
  right: -7px;
  display: flex;
  align-items: center;
  justify-content: center;
`;
