import React, { useState } from "react";
import styled from "styled-components";
import {
  ICity,
  ICityFleet,
  ICityResource,
  IMapCity,
  IMapFleetWarshipsData,
  TTask,
} from "../../types/types";
import { SCloseButton, SContent, SH1 } from "../styles";
import { useLocation, useNavigate } from "react-router-dom";
import { MapCell } from "./MapCell";
import { useFetchMap } from "../../hooks/useFetchMap";
import Modal from "react-modal";
import { SendingFleet } from "../SendingFleet/SendingFleet";
import { Icon } from "../Common/Icon";
import { useFetchAdventure } from "../../hooks/useFetchAdventure";

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
  const [isPopoverOpen, setIsPopoverOpen] = useState(false);
  const [fleetTask, setFleetTask] = useState<TTask>("trade");
  const [targetCity, setTargetCity] = useState<IMapCity | undefined>(undefined);

  const [cells, setCells] = useState<{ id: number }[]>(() => {
    let tCells = [];

    for (let i = 0; i < size; i++) {
      tCells[i] = {
        id: i,
      };
    }

    return tCells;
  });

  const queryMap = useFetchMap();
  const queryMapAdventure = useFetchAdventure();

  const getCity = (y: number, x: number) => {
    return mapCities?.find((city) => city.coordX === x && city.coordY === y);
  };

  const location = useLocation();
  const searchParams = new URLSearchParams(location.search);
  const isAdventure = searchParams.get("adventure") === "1";

  const navigate = useNavigate();

  let mapCities: IMapCity[] = queryMap?.data?.cities || [];
  let adventureLvl = 0;
  let adventureWarships: IMapFleetWarshipsData[] = [];

  if (isAdventure) {
    mapCities = queryMapAdventure?.data?.cities || [];
    adventureLvl = queryMapAdventure?.data?.adventureLevel || 0;
    adventureWarships = queryMapAdventure?.data?.warships || [];
  }

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

  if (queryMap.isFetching || queryMapAdventure.isFetching) {
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

              <button
                className={"btn btn-primary"}
                onClick={() => {
                  navigate("/map?adventure=1");
                }}
              >
                Adventure
              </button>
            </SMapActionDescription>
          </SMapAction>
        </SColumn>
      </SRow>
      <SH1>
        {isAdventure
          ? "Adventure, " + adventureLvl + " lvl."
          : "Your Archipelago"}
      </SH1>
      <SCells>
        {cells?.map((cell, index) => {
          const y = Math.floor(index / 5) + 1;
          const x = (index % 5) + 1;
          const mapCity = getCity(y, x);
          const isCity = Boolean(mapCity);
          const isPirates = mapCity?.cityTypeId === 2;

          return (
            <MapCell
              key={cell.id}
              isCity={isCity}
              city={mapCity}
              isPirates={isPirates}
              isFleetMovingToIsland={isFleetMovingToIsland(mapCity?.id)}
              isIslandRaided={isIslandRaided(mapCity?.id)}
              isAdventure={isAdventure}
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
          isAdventure={isAdventure}
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
