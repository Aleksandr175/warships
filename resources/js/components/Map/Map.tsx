import React, { useState } from "react";
import { useEffect } from "react";
import { httpClient } from "../../httpClient/httpClient";
import styled from "styled-components";
import {
  ICity,
  ICityFleet,
  ICityResource,
  IMapCity,
  IMapFleetWarshipsData,
  TTask,
  TType,
} from "../../types/types";
import { SCloseButton, SContent, SH1 } from "../styles";
import { useNavigate } from "react-router-dom";
import { MapCell } from "./MapCell";
import { useFetchMap } from "../../hooks/useFetchMap";
import Modal from "react-modal";
import { SendingFleet } from "../SendingFleet/SendingFleet";
import { Icon } from "../Common/Icon";

const customStyles = {
  overlay: {
    background: "none",
    zIndex: 1,
  },
  content: {
    width: "600px",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
    background: "var(--background-color)",
    borderRadius: "var(--block-border-radius)",
    padding: "var(--block-padding)",
  },
};

export const Map = ({
  fleets,
  cities,
  city,
  cityResources,
}: {
  fleets: ICityFleet[] | undefined;
  cities: ICity[];
  city: ICity;
  cityResources: ICityResource[];
}) => {
  const size = 5 * 5;
  const [mapCities, setMapCities] = useState<IMapCity[]>([]);
  const [adventureLvl, setAdventureLvl] = useState<number>(0);
  const [adventureWarships, setAdventureWarships] = useState<
    IMapFleetWarshipsData[]
  >([]);
  const [isPopoverOpen, setIsPopoverOpen] = useState(false);
  const [fleetTask, setFleetTask] = useState<TTask>("trade");
  const [targetCity, setTargetCity] = useState<IMapCity | undefined>(undefined);

  const [type, setType] = useState<TType>("map");
  const [cells, setCells] = useState<{ id: number }[]>(() => {
    let tCells = [];

    for (let i = 0; i < size; i++) {
      tCells[i] = {
        id: i,
      };
    }

    return tCells;
  });
  const [isLoading, setIsLoading] = useState(true);

  const queryMap = useFetchMap();

  useEffect(() => {
    if (queryMap?.data && adventureLvl === 0) {
      setMapCities(queryMap.data.cities);
    }
  }, [queryMap?.data]);

  const isCityHere = (y: number, x: number): boolean => {
    return (
      mapCities?.findIndex((city) => city.coordX === x && city.coordY === y) >
      -1
    );
  };

  const getCity = (y: number, x: number) => {
    return mapCities?.find((city) => city.coordX === x && city.coordY === y);
  };

  const navigate = useNavigate();

  const getAdventure = () => {
    setIsLoading(true);
    setType("adventure");

    httpClient.get("/map/adventure").then((response) => {
      setMapCities(response.data.cities);
      setAdventureWarships(response.data.warships);
      setAdventureLvl(response.data.adventureLevel || 0);

      setIsLoading(false);
    });
  };

  const isFleetMovingToIsland = (cityId: number | undefined): boolean => {
    return (
      (fleets || []).findIndex((fleet) => fleet.targetCityId === cityId) > -1
    );
  };

  const isIslandRaided = (cityId: number | undefined) => {
    return !!mapCities?.find((city) => city.id === cityId)?.raided;
  };

  const getWarships = (cityId: number | undefined) => {
    if (cityId) {
      return adventureWarships.filter(
        (adventureWarshipsData) => adventureWarshipsData.cityId === cityId
      );
    }

    return [];
  };

  const openSendingFleetPopup = (city: IMapCity | undefined, task: TTask) => {
    setTargetCity(city);
    setFleetTask(task);
    setIsPopoverOpen(true);
  };

  const closeModal = () => {
    setFleetTask("trade");
    setIsPopoverOpen(false);
  };

  const isTakingOverDisabled = () => {
    let disabled = false;

    if (
      queryMap?.data?.availableCitiesData?.availableCities &&
      queryMap?.data?.availableCitiesData?.availableCities <= cities.length
    ) {
      disabled = true;
    }

    console.log("disabled", disabled);

    return disabled;
  };

  if (queryMap.isPending) {
    return <>Loading...</>;
  }

  return (
    <SContent>
      <SRow>
        <SColumn>
          <SMapAction>
            <SMapActionLogo
              style={{
                backgroundImage: 'url("../../../images/islands/1.svg")',
              }}
            ></SMapActionLogo>
            <SMapActionDescription>
              <strong>Expedition</strong>

              <p>
                Send fleet to unknown islands to find gold or rare resources
              </p>

              <button
                className={"btn btn-primary"}
                onClick={() => openSendingFleetPopup(undefined, "expedition")}
              >
                Send
              </button>
            </SMapActionDescription>
          </SMapAction>
        </SColumn>
        <SColumn>
          <SMapAction>
            <SMapActionLogoTrade
              style={{
                backgroundImage: 'url("../../../images/icons/directions.svg")',
              }}
            ></SMapActionLogoTrade>
            <SMapActionDescription>
              <strong>Trade</strong>

              <p>Get some gold with trading!</p>

              <button
                className={"btn btn-primary"}
                onClick={() => openSendingFleetPopup(undefined, "trade")}
              >
                Trade
              </button>
            </SMapActionDescription>
          </SMapAction>
        </SColumn>
        <SColumn>
          <SMapAction>
            <SMapActionLogo
              style={{
                backgroundImage: 'url("../../../images/islands/pirates/1.svg")',
              }}
            ></SMapActionLogo>
            <SMapActionDescription>
              <strong>Adventure</strong>

              <p>Conquer unknown islands to get big treasure!</p>

              <button className={"btn btn-primary"} onClick={getAdventure}>
                Adventure
              </button>
            </SMapActionDescription>
          </SMapAction>
        </SColumn>
      </SRow>
      <SH1>
        {type === "map"
          ? "Your Archipelago"
          : "Adventure, " + adventureLvl + " lvl."}
      </SH1>
      <SCells>
        {cells?.map((cell, index) => {
          const y = Math.floor(index / 5) + 1;
          const x = (index % 5) + 1;
          const mapCity = getCity(y, x);
          const isCity = isCityHere(y, x);
          const isPirates = mapCity?.cityTypeId === 2;

          return (
            <MapCell
              key={cell.id}
              isCity={isCity}
              city={mapCity}
              isPirates={isPirates}
              isFleetMovingToIsland={isFleetMovingToIsland(mapCity?.id)}
              isIslandRaided={isIslandRaided(mapCity?.id)}
              isAdventure={!!adventureLvl}
              warships={getWarships(mapCity?.id)}
              onSendingFleet={(city, task) => openSendingFleetPopup(city, task)}
              currentCityId={city?.id || 0}
              isTakingOverDisabled={isTakingOverDisabled()}
            />
          );
        })}
      </SCells>

      <Modal
        isOpen={isPopoverOpen}
        style={customStyles}
        contentLabel="Sending Fleet"
      >
        <SCloseButton onClick={closeModal}>
          <Icon title={"cross"} size={"big"} />
        </SCloseButton>

        <SendingFleet
          city={city}
          targetCity={targetCity}
          cityResources={cityResources}
          fleetTask={fleetTask}
          isAdventure={!!adventureLvl}
        />
      </Modal>
    </SContent>
  );
};

const SCells = styled.div`
  width: 700px;
  height: 700px;
`;

const SRow = styled.div`
  display: flex;
  align-items: flex-start;
  gap: 20px;
  margin-bottom: 30px;
`;

const SColumn = styled.div`
  width: 33%;
`;

const SMapAction = styled.div`
  display: flex;
  gap: 10px;
`;

const SMapActionLogo = styled.div`
  width: 100px;
  height: 100px;
  min-width: 100px;
  background-size: contain;
  background-repeat: no-repeat;
`;

const SMapActionLogoTrade = styled(SMapActionLogo)`
  width: 70px;
  height: 70px;
  min-width: 70px;
  margin-top: 15px;
`;

const SMapActionDescription = styled.div`
  p {
    font-size: 12px;
    color: #949494;
  }
`;
