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
import { SendingFleet } from "./SendingFleet/SendingFleet";
import { useAppLogic } from "./hooks/useAppLogic";
import { Fleets } from "./Fleets";
import { Logs } from "./Logs/Logs";
import { Log } from "./Logs/Log";
import { Icon } from "./Common/Icon";
import { SAppContainer, SColumn } from "./styles";
import { Messages } from "./Messages/Messages";
import { Message } from "./Messages/Message";
import { Refining } from "./Refining/Refining";

const App = () => {
  const {
    isLoading,
    city,
    cityResources,
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
    dictionaries,
    userId,
    logout,
    unreadMessagesNumber,
  } = useAppLogic();

  if (isLoading) {
    return <></>;
  }

  return (
    <Router>
      <SAppContainer className={"container"}>
        {city && cities && (
          <div className={"row"}>
            <div className={"col-12"}>
              <SHeaderBlock>
                <SIslands>
                  {cities.map((c) => {
                    return (
                      <SIsland
                        key={c.id}
                        /*active={c.id === city.id}*/
                        onClick={() => {
                          /*selectCity(c)*/
                        }}
                      >
                        <Icon title={"island"} />[{city.coordX}:{city.coordY}]{" "}
                        {c.title}
                      </SIsland>
                    );
                  })}
                </SIslands>

                <SMessagesWrapper>
                  <NavLink to={"/messages"}>
                    <Icon title={"messages"} />
                    {unreadMessagesNumber > 0 && (
                      <SMessagesNumber>
                        {unreadMessagesNumber > 9 ? `9+` : unreadMessagesNumber}
                      </SMessagesNumber>
                    )}
                  </NavLink>
                </SMessagesWrapper>
              </SHeaderBlock>
            </div>
          </div>
        )}
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
                to={"/warships"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Warships
              </NavLink>
              <NavLink
                to={"/refining"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Refining
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
                to={"/sending-fleets"}
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
                <div className={"col-12"}>
                  <SColumn>
                    <CityResources
                      cityResources={cityResources!}
                      buildings={buildings}
                      city={city}
                    />
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
                        city={city}
                        updateCityResources={updateCityResources}
                        cityResources={city.resources}
                        getBuildings={getBuildings}
                        buildings={buildings}
                        setBuildings={setBuildings}
                        setQueue={setQueue}
                        queue={queue}
                        // TODO: userResearches should not be in dictionaries
                        researches={dictionaries.userResearches}
                      />
                    }
                  />
                  <Route
                    path={"researches"}
                    element={
                      <Researches
                        cityId={city.id}
                        updateCityResources={updateCityResources}
                        cityResources={city.resources}
                        // TODO: userResearches should not be in dictionaries
                        researches={dictionaries.userResearches}
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
                        updateCityResources={updateCityResources}
                        cityResources={city.resources}
                        getWarships={getWarships}
                        warships={warships}
                        setWarships={setWarships}
                        setQueue={setQueueWarship}
                        queue={queueWarship}
                        // TODO: userResearches should not be in dictionaries
                        researches={dictionaries.userResearches}
                        buildings={buildings!}
                      />
                    }
                  />
                  <Route
                    path={"sending-fleets"}
                    element={
                      <SendingFleet
                        warships={warships}
                        cities={cities}
                        city={city}
                        cityResources={city.resources}
                      />
                    }
                  />
                  <Route path={"map"} element={<Map fleets={fleets} />} />
                  <Route
                    path={"refining"}
                    element={
                      <Refining city={city} cityResources={city.resources} />
                    }
                  />
                  <Route
                    path="logs/:id"
                    element={<Log userId={userId || 0} />}
                  />
                  <Route
                    path={"logs"}
                    element={<Logs userId={userId || 0} />}
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

const SIslands = styled.div`
  display: flex;
  gap: calc(2 * var(--block-padding));
`;

const SIsland = styled.div`
  > i {
    padding-right: 5px;
  }
`;

const SHeaderBlock = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;

  background: var(--background-color);
  padding: 10px var(--block-padding);
  border-radius: 0 0 var(--block-border-radius) var(--block-border-radius);
  margin-bottom: var(--block-gutter-y);
`;
