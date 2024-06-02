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
import styled, { css } from "styled-components";
import { CityResources } from "./CityResources";
import { Warships } from "./Warships/Warships";
import { Map } from "./Map/Map";
import { useAppLogic } from "./hooks/useAppLogic";
import { Fleets } from "./Fleets";
import { Icon } from "./Common/Icon";
import { SAppContainer, SColumn } from "./styles";
import { Messages } from "./Messages/Messages";
import { Message } from "./Messages/Message";
import { Refining } from "./Refining/Refining";
import { WarshipsImprovements } from "./WarshipsImprovements/WarshipsImprovements";

const App = () => {
  const {
    city,
    updateCityResources,
    cities,
    fleets,
    fleetDetails,
    fleetCitiesDictionary,
    fleetsIncoming,
    dictionaries,
    userId,
    logout,
    unreadMessagesNumber,
    selectCity,
  } = useAppLogic();

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
                        active={c.id === city.id}
                        onClick={() => {
                          selectCity(c);
                        }}
                      >
                        <Icon title={"island"} />[{c.coordX}:{c.coordY}]{" "}
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
                to={"/warships-improvements"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Warships Improvements
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
                to={"/map"}
                className={({ isActive }) =>
                  isActive ? "link selected-link" : "link"
                }
              >
                Map
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
                    <CityResources city={city} />
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
                        // TODO: userResearches should not be in dictionaries
                        researches={dictionaries.userResearches}
                      />
                    }
                  />
                  <Route
                    path={"researches"}
                    element={<Researches cityId={city.id} />}
                  />
                  <Route
                    path={"warships"}
                    element={
                      <Warships city={city} cityResources={city.resources} />
                    }
                  />
                  <Route
                    path={"warships-improvements"}
                    element={<WarshipsImprovements />}
                  />
                  <Route
                    path={"map"}
                    element={
                      <Map
                        fleets={fleets}
                        cities={cities}
                        city={city}
                        cityResources={city.resources}
                      />
                    }
                  />
                  <Route
                    path={"refining"}
                    element={
                      <Refining
                        city={city}
                        cityResources={city.resources}
                        updateCityResources={updateCityResources}
                      />
                    }
                  />
                  <Route path={"messages"} element={<Messages />} />
                  <Route
                    path={"messages/:id"}
                    element={<Message userId={userId || 0} />}
                  />
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

const SIsland = styled.div<{ active?: boolean }>`
  > i {
    padding-right: 5px;
  }

  ${({ active }) =>
    active &&
    css`
      font-weight: 700;
    `};
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
