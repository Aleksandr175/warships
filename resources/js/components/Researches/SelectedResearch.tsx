import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime } from "../../utils";
import React from "react";
import styled from "styled-components";
import { httpClient } from "../../httpClient/httpClient";
import {
  ICityResearchQueue,
  ICityResources,
  IResearch,
  IResearchDependency,
  IResearchResource,
  IUserResearch,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";

interface IProps {
  selectedResearchId: number;
  cityId: number;
  researchesDictionary: IResearch[];
  researchResourcesDictionary: IResearchResource[];
  researchDependencyDictionary: IResearchDependency[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  queue?: ICityResearchQueue;
  setQueue: (q: ICityResearchQueue | undefined) => void;
  researches: IUserResearch[];
  timeLeft: number;
  getLvl: (buildingId: number) => number;
}

export const SelectedResearch = ({
  selectedResearchId,
  researches,
  cityId,
  researchResourcesDictionary,
  researchDependencyDictionary,
  updateCityResources,
  cityResources,
  queue,
  setQueue,
  researchesDictionary,
  timeLeft,
  getLvl,
}: IProps) => {
  const selectedResearch = getResearch(selectedResearchId)!;
  const lvl = getLvl(selectedResearchId);
  const nextLvl = lvl + 1;

  function getResearch(researchId: number): IResearch | undefined {
    return researchesDictionary.find((research) => research.id === researchId);
  }

  function isResearchInProcess() {
    return queue && queue.researchId === selectedResearchId;
  }

  const researchResources = getResourcesForResearch(
    selectedResearchId,
    lvl + 1
  );
  const gold = researchResources?.gold || 0;
  const population = researchResources?.population || 0;
  const time = researchResources?.time || 0;

  function getResourcesForResearch(resourceId: number, lvl: number) {
    return researchResourcesDictionary.find(
      (rr) => rr.researchId === resourceId && rr.lvl === lvl
    );
  }

  function isResearchDisabled() {
    return (
      gold > cityResources.gold ||
      population > cityResources.population ||
      !researchResources
    );
  }

  const {
    hasRequirements,
    hasAllRequirements,
    getRequirements,
    getRequiredItem,
  } = useRequirementsLogic({
    dependencyDictionary: researchDependencyDictionary,
    researchesDictionary,
    researches,
  });

  function run(researchId: number) {
    httpClient
      .post("/researches/" + researchId + "/run", {
        cityId,
        researchId,
      })
      .then((response) => {
        //setResearches(response.data.buildings);
        setQueue(response.data.queue);
        updateCityResources(response.data.cityResources);
      });
  }

  function cancel(researchId: number) {
    httpClient
      .post("/researches/" + researchId + "/cancel")
      .then((response) => {
        /*setBuildings(response.data.buildings);*/
        setQueue(undefined);
        updateCityResources(response.data.cityResources);
      });
  }

  return (
    <SSelectedItem className={"row"}>
      <div className={"col-4"}>
        <SCardWrapper>
          <Card
            object={selectedResearch}
            qty={lvl}
            timer={queue?.researchId === selectedResearchId ? timeLeft : 0}
            imagePath={"researches"}
          />
        </SCardWrapper>
      </div>
      <div className={"col-8"}>
        <SH2>{selectedResearch?.title}</SH2>
        <div>
          {Boolean(gold || population) && (
            <>
              <SText>Required resources:</SText>
              <SParams>
                <SParam>
                  <Icon title={"gold"} /> {gold}
                </SParam>
                <SParam>
                  <Icon title={"worker"} /> {population}
                </SParam>
                <SParam>
                  <Icon title={"time"} /> {convertSecondsToTime(time)}
                </SParam>
              </SParams>
            </>
          )}

          {hasRequirements("research", selectedResearchId, nextLvl) && (
            <>
              <SText>It requires:</SText>
              {getRequirements("research", selectedResearchId, nextLvl)?.map(
                (requirement) => {
                  const requiredItem = getRequiredItem(requirement);

                  return (
                    <SText>
                      {requiredItem?.title}, {requirement.requiredEntityLvl} lvl
                    </SText>
                  );
                }
              )}
            </>
          )}
        </div>
        <SButtonsBlock>
          {!isResearchInProcess() && (
            <button
              className={"btn btn-primary"}
              disabled={
                isResearchDisabled() ||
                !hasAllRequirements("research", selectedResearchId, nextLvl)
              }
              onClick={() => {
                run(selectedResearchId);
              }}
            >
              Research
            </button>
          )}

          {isResearchInProcess() && (
            <button
              className={"btn btn-warning"}
              onClick={() => {
                cancel(selectedResearchId);
              }}
            >
              Cancel
            </button>
          )}
        </SButtonsBlock>
        <SText>{selectedResearch?.description}</SText>
      </div>
    </SSelectedItem>
  );
};

const SSelectedItem = styled.div`
  margin-bottom: calc(var(--block-gutter-y) * 2);
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;
