import React, { useState } from "react";
import { Popover } from "react-tiny-popover";
import styled, { css } from "styled-components";
import {
  IAvailableCitiesData,
  IFleetWarshipsData,
  IMapCity,
  TTask,
} from "../../types/types";
import {
  SCloseButton,
  SPopoverButtons,
  SPopoverHeader,
  SPopoverWrapper,
} from "../styles";
import { Icon } from "../Common/Icon";
import { FleetWarships } from "../Common/FleetWarships";
import { getResearchTitleById, getResourceSlug } from "../../utils";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useFetchUserData } from "../../hooks/useFetchUserData";

interface IProps {
  city: IMapCity | undefined;
  isCity: boolean;
  isPirates: boolean;
  isIslandRaided: boolean;
  isAttackFleetMovingToIsland: boolean;
  isAdventure: boolean;
  warships: IFleetWarshipsData[];
  onSendingFleet: (city: IMapCity, task: TTask) => void;
  currentCityId: number;
  isTakingOverDisabled?: boolean;
  availableCitiesData?: IAvailableCitiesData;
}

export const MapCell = ({
  city,
  isCity,
  isPirates,
  isIslandRaided,
  isAttackFleetMovingToIsland,
  isAdventure,
  warships,
  onSendingFleet,
  currentCityId,
  isTakingOverDisabled,
  availableCitiesData,
}: IProps) => {
  const { data: dictionaries } = useFetchDictionaries();
  const { data: userData } = useFetchUserData();
  const userId = userData?.data.userId;

  const [isPopoverOpen, setIsPopoverOpen] = useState(false);

  const sortedResources = (city?.resourcesProductionCoefficient || []).sort(
    (a, b) => b.coefficient - a.coefficient
  );

  const mainResources = sortedResources.filter(
    (resource) => resource.coefficient === sortedResources[0].coefficient
  );

  if (!dictionaries) {
    return null;
  }

  const handlePopoverClose = () => setIsPopoverOpen(false);

  const handlePopoverOpen = () => setIsPopoverOpen(true);

  const handleFleetAction = (task: TTask) => {
    if (city) {
      onSendingFleet(city, task);
      handlePopoverClose();
    }
  };

  return (
    <SCell>
      {city && isCity && (
        <>
          <Popover
            isOpen={isPopoverOpen}
            onClickOutside={handlePopoverClose}
            positions={["right", "left"]}
            content={
              <SPopoverWrapper>
                <SPopoverHeader>{city.title}</SPopoverHeader>
                {isAdventure &&
                  (city.resources?.length > 0 || warships.length > 0) && (
                    <SInfoWrapper>
                      <SResources>
                        {city.resources
                          ?.filter((resource) => resource.qty > 0)
                          .map((resource) => (
                            <SResource key={resource.resourceId}>
                              <Icon
                                title={getResourceSlug(
                                  dictionaries.resourcesDictionary,
                                  resource.resourceId
                                )}
                              />
                              {resource.qty}
                            </SResource>
                          ))}
                      </SResources>
                      {warships.length > 0 && (
                        <FleetWarships warships={warships} />
                      )}
                    </SInfoWrapper>
                  )}

                {!isAdventure && !city.userId && isTakingOverDisabled && (
                  <>
                    <p>Requirements:</p>
                    <p>
                      {getResearchTitleById(
                        dictionaries.researches,
                        availableCitiesData?.requirementsForNextCity.researchId
                      )}
                      : {availableCitiesData?.requirementsForNextCity.lvl}
                    </p>
                  </>
                )}

                <SCloseButton onClick={handlePopoverClose}>
                  <Icon title="cross" />
                </SCloseButton>

                {isIslandRaided && <p>Island is already raided</p>}

                {currentCityId === city.id && <p>Selected Island</p>}

                {currentCityId !== city.id && !isIslandRaided && (
                  <SPopoverButtons>
                    {isAdventure || isPirates ? (
                      <button
                        className="btn btn-primary"
                        onClick={() => handleFleetAction("attack")}
                      >
                        Attack
                      </button>
                    ) : (
                      <>
                        {city.userId === userId && (
                          <>
                            <button
                              className="btn btn-primary"
                              onClick={() => handleFleetAction("move")}
                            >
                              Move
                            </button>
                            <button
                              className="btn btn-primary"
                              onClick={() => handleFleetAction("transport")}
                            >
                              Transport
                            </button>
                          </>
                        )}
                        {!city.userId && (
                          <button
                            className="btn btn-primary"
                            disabled={isTakingOverDisabled}
                            onClick={() => handleFleetAction("takeOver")}
                          >
                            Take over
                          </button>
                        )}
                      </>
                    )}
                  </SPopoverButtons>
                )}
              </SPopoverWrapper>
            }
          >
            <SIsland
              islandType={isPirates ? "pirates" : ""}
              type={city.cityAppearanceId}
              onClick={handlePopoverOpen}
            />
          </Popover>
          {isAttackFleetMovingToIsland && (
            <SCityMarkFleet>
              <Icon title="attack" />
            </SCityMarkFleet>
          )}
          {isIslandRaided && (
            <SCityMarkStatus>
              <Icon title="check" />
            </SCityMarkStatus>
          )}
          <SCityMainResourceMark>
            {mainResources.map((resource) => (
              <Icon
                key={resource.resourceId}
                title={getResourceSlug(
                  dictionaries.resourcesDictionary,
                  resource.resourceId
                )}
              />
            ))}
          </SCityMainResourceMark>
        </>
      )}
    </SCell>
  );
};

const SCell = styled.div`
  position: relative;
  float: left;
  width: 140px;
  height: 140px;
  background: url("../../../images/islands/ocean.svg") no-repeat;
  background-size: contain;
`;

const SCityMarkFleet = styled.div`
  position: absolute;
  top: 0;
  left: 0;
  width: 32px;
  height: 32px;
`;

const SCityMarkStatus = styled.div`
  position: absolute;
  top: 0;
  right: 0;
  width: 32px;
  height: 32px;
`;

const SCityMainResourceMark = styled.div`
  position: absolute;
  bottom: 0;
  left: 0;
  width: 96px;
  height: 32px;
`;

const SIsland = styled.div<{ type?: number; islandType?: string }>`
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  cursor: pointer;
  ${({ type, islandType }) =>
    type &&
    css`
      background: url("../../../images/islands/${islandType
          ? islandType + "/"
          : ""}${type}.svg")
        no-repeat;
      background-size: contain;
    `}
`;

const SResources = styled.div`
  display: flex;
  align-items: center;
  gap: 20px;
`;

const SResource = styled.div`
  display: flex;
  align-items: center;
`;

const SInfoWrapper = styled.div`
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
`;
