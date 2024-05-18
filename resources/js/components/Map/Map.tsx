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
import { MapAction } from "./MapAction";

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
  const size = 25; // 5 * 5 grid
  const [isPopoverOpen, setIsPopoverOpen] = useState(false);
  const [fleetTask, setFleetTask] = useState<TTask>("trade");
  const [targetCity, setTargetCity] = useState<IMapCity | undefined>(undefined);

  const queryMap = useFetchMap();
  const queryMapAdventure = useFetchAdventure();

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

  const cells = Array.from({ length: size }, (_, i) => ({ id: i }));

  const getCity = (y: number, x: number): IMapCity | undefined =>
    mapCities?.find((city) => city.coordX === x && city.coordY === y);

  const isFleetMovingToIsland = (cityId: number | undefined) =>
    (fleets || []).some((fleet) => fleet.targetCityId === cityId);

  const isIslandRaided = (cityId: number | undefined) =>
    !!mapCities?.find((city) => city.id === cityId)?.raided;

  const getWarships = (cityId: number | undefined) =>
    cityId
      ? adventureWarships.filter(
          (adventureWarshipsData) => adventureWarshipsData.cityId === cityId
        )
      : [];

  const openSendingFleetPopup = (city: IMapCity | undefined, task: TTask) => {
    setTargetCity(city);
    setFleetTask(task);
    setIsPopoverOpen(true);
  };

  const closeModal = () => {
    setFleetTask("trade");
    setIsPopoverOpen(false);
  };

  const availableCitiesData = queryMap?.data?.availableCitiesData;

  const isTakingOverDisabled = () =>
    (queryMap?.data?.availableCitiesData?.availableCities || 0) <=
    cities.length;

  /* if (queryMap.isFetching || queryMapAdventure.isFetching) {
    return <>Loading...</>;
  } */

  return (
    <SContent>
      <SRow>
        <MapAction
          title="Expedition"
          description="Send fleet to unknown islands to find gold or rare resources"
          logoUrl="../../../images/islands/1.svg"
          onClick={() => openSendingFleetPopup(undefined, "expedition")}
        />
        <MapAction
          title="Trade"
          description="Get some gold with trading!"
          logoUrl="../../../images/icons/directions.svg"
          logoStyle={{
            width: "70px",
            height: "70px",
            minWidth: "70px",
            marginTop: "15px",
          }}
          onClick={() => openSendingFleetPopup(undefined, "trade")}
        />
        <MapAction
          title="Adventure"
          description="Conquer unknown islands to get big treasure!"
          logoUrl="../../../images/islands/pirates/1.svg"
          onClick={() => navigate("/map?adventure=1")}
        />
      </SRow>
      <SH1>
        {isAdventure ? `Adventure, ${adventureLvl} lvl.` : "Your Archipelago"}
      </SH1>
      <SCells>
        {cells.map((cell, index) => {
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
              availableCitiesData={availableCitiesData}
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
