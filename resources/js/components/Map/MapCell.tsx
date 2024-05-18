import { Popover } from "react-tiny-popover";
import { Icon } from "../Common/Icon";
import React, { useState } from "react";
import styled, { css } from "styled-components";
import { IFleetWarshipsData, IMapCity, TTask } from "../../types/types";
import {
  SCloseButton,
  SPopoverButtons,
  SPopoverHeader,
  SPopoverWrapper,
} from "../styles";
import { FleetWarships } from "../Common/FleetWarships";
import { useNavigate } from "react-router-dom";
import { getResourceSlug } from "../../utils";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useFetchUserData } from "../../hooks/useFetchUserData";

interface IProps {
  city: IMapCity | undefined;
  isCity: boolean;
  isPirates: boolean;
  isIslandRaided: boolean;
  isFleetMovingToIsland: boolean;
  isAdventure: boolean;
  warships: IFleetWarshipsData[];
  onSendingFleet: (city: IMapCity, task: TTask) => void;
  currentCityId: number;
  isTakingOverDisabled?: boolean;
}
export const MapCell = ({
  city,
  isCity,
  isPirates,
  isFleetMovingToIsland,
  isIslandRaided,
  isAdventure,
  warships,
  onSendingFleet,
  currentCityId,
  isTakingOverDisabled,
}: IProps) => {
  const navigate = useNavigate();
  const queryDictionaries = useFetchDictionaries();

  const queryUserData = useFetchUserData();
  const userId = queryUserData?.data?.userId;

  const dictionaries = queryDictionaries.data;

  const [isPopoverOpen, setIsPopoverOpen] = useState(false);

  // Sort the array of objects by coefficient in descending order
  const sortedResources = (city?.resourcesProductionCoefficient || []).sort(
    (a, b) => b.coefficient - a.coefficient
  );

  const mainResources =
    sortedResources?.filter((resource) => {
      return resource.coefficient === sortedResources[0].coefficient;
    }) || [];

  if (!dictionaries) {
    return <></>;
  }

  return (
    <SCell isHabited={isCity}>
      {city && isCity && (
        <>
          <Popover
            isOpen={isPopoverOpen}
            onClickOutside={() => setIsPopoverOpen(false)}
            positions={["right", "left"]} // preferred positions by priority
            content={
              <SPopoverWrapper>
                <SPopoverHeader>{city.title}</SPopoverHeader>
                {isAdventure && (
                  <SInfoWrapper>
                    <SResources>
                      {city.resources
                        ?.filter((resource) => resource.qty > 0)
                        .map((resource) => {
                          return (
                            <SResource key={resource.resourceId}>
                              <Icon
                                title={getResourceSlug(
                                  dictionaries.resourcesDictionary,
                                  resource.resourceId
                                )}
                              />
                              {resource.qty}
                            </SResource>
                          );
                        })}
                    </SResources>

                    {warships && warships.length > 0 && (
                      <FleetWarships warships={warships} />
                    )}
                  </SInfoWrapper>
                )}

                <SCloseButton onClick={() => setIsPopoverOpen(false)}>
                  <Icon title={"cross"} />
                </SCloseButton>

                {currentCityId !== city.id && (
                  <SPopoverButtons>
                    {isAdventure || isPirates ? (
                      <button
                        className={"btn btn-primary"}
                        onClick={() => {
                          onSendingFleet(city, "attack");
                          setIsPopoverOpen(false);
                        }}
                      >
                        Attack
                      </button>
                    ) : (
                      <>
                        <button
                          className={"btn btn-primary"}
                          onClick={() => {
                            onSendingFleet(city, "move");
                            setIsPopoverOpen(false);
                          }}
                        >
                          Move
                        </button>
                        <button
                          className={"btn btn-primary"}
                          onClick={() => {
                            onSendingFleet(city, "transport");
                            setIsPopoverOpen(false);
                          }}
                        >
                          Transport
                        </button>
                        {!isPirates && !city.userId && (
                          <button
                            className={"btn btn-primary"}
                            disabled={isTakingOverDisabled}
                            onClick={() => {
                              onSendingFleet(city, "takeOver");
                              setIsPopoverOpen(false);
                            }}
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
              type={city?.cityAppearanceId}
              onClick={() => {
                setIsPopoverOpen(!isPopoverOpen);
              }}
            />
          </Popover>
          {isFleetMovingToIsland && (
            <SCityMarkFleet>
              <Icon title={"attack"} />
            </SCityMarkFleet>
          )}
          {isIslandRaided && (
            <SCityMarkStatus>
              <Icon title={"check"} />
            </SCityMarkStatus>
          )}

          {mainResources && (
            <SCityMainResourceMark>
              {mainResources.map((resource) => {
                return (
                  <Icon
                    title={getResourceSlug(
                      dictionaries.resourcesDictionary,
                      resource.resourceId
                    )}
                  />
                );
              })}
            </SCityMainResourceMark>
          )}
        </>
      )}
    </SCell>
  );
};
const SCell = styled.div<{ isHabited?: boolean }>`
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
    type
      ? css`
          background: url("../../../images/islands/${islandType
              ? "/" + islandType + "/"
              : ""}${type}.svg")
            no-repeat;
          background-size: contain;
        `
      : ""};
`;

const SResources = styled.div`
  display: flex;
  align-items: center;
  gap: 20px;
`;

const SResource = styled.div`
  position: relative;
  display: flex;
  align-items: center;
`;

const SInfoWrapper = styled.div`
  display: flex;
  flex-direction: column;
  gap: 10px;

  margin-bottom: 20px;
`;
