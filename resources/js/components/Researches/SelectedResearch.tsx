import { Card } from "../Common/Card";
import { SButtonsBlock, SH2, SParam, SParams, SText } from "../styles";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime, getResourceSlug } from "../../utils";
import React from "react";
import styled from "styled-components";
import { httpClient } from "../../httpClient/httpClient";
import {
  ICityResearchQueue,
  ICityResource,
  IResearch,
  IResearchResource,
  IUserResearch,
} from "../../types/types";
import { useRequirementsLogic } from "../hooks/useRequirementsLogic";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";

interface IProps {
  selectedResearchId: number;
  cityId: number;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
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
  updateCityResources,
  cityResources,
  queue,
  setQueue,
  timeLeft,
  getLvl,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const selectedResearch = getResearch(selectedResearchId)!;
  const lvl = getLvl(selectedResearchId);
  const nextLvl = lvl + 1;

  function getResearch(researchId: number): IResearch | undefined {
    return dictionaries?.researches.find(
      (research) => research.id === researchId
    );
  }

  const isCurrentResearchInProcess =
    queue && queue.researchId === selectedResearchId;
  const isSomeResearchInProcess = queue && queue.researchId > 0;

  const requiredResources = getResourcesForResearch(
    selectedResearchId,
    lvl + 1
  );
  const timeRequired = requiredResources[0]?.timeRequired || 0;

  function getResourcesForResearch(
    researchId: number,
    lvl: number
  ): IResearchResource[] {
    return (
      dictionaries?.researchResources.filter(
        (rr) => rr.researchId === researchId && rr.lvl === lvl
      ) || []
    );
  }

  function isResearchDisabled() {
    for (const resource of requiredResources) {
      const cityResource = cityResources.find(
        (cr) => cr.resourceId === resource.resourceId
      );

      if (!cityResource || cityResource.qty < resource.qty) {
        return true;
      }
    }

    if (!requiredResources?.length) {
      return true;
    }

    return false;
  }

  const {
    hasRequirements,
    hasAllRequirements,
    getRequirements,
    getRequiredItem,
  } = useRequirementsLogic({
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

  if (!dictionaries) {
    return null;
  }

  return (
    <SSelectedItem className={"row"}>
      <div className={"col-4"}>
        <SCardWrapper>
          <Card
            objectId={selectedResearch.id}
            labelText={lvl}
            timer={queue?.researchId === selectedResearchId ? timeLeft : 0}
            imagePath={"researches"}
          />
        </SCardWrapper>
      </div>
      <div className={"col-8"}>
        <SH2>{selectedResearch?.title}</SH2>
        <div>
          {requiredResources?.length > 0 && (
            <>
              <SText>Required resources:</SText>
              <SParams>
                {requiredResources.map((resource) => {
                  return (
                    <SParam key={resource.resourceId}>
                      <Icon
                        title={getResourceSlug(
                          dictionaries.resourcesDictionary,
                          resource.resourceId
                        )}
                      />
                      {resource.qty}
                    </SParam>
                  );
                })}
                <SParam>
                  <Icon title={"time"} /> {convertSecondsToTime(timeRequired)}
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
          {!isCurrentResearchInProcess && (
            <button
              className={"btn btn-primary"}
              disabled={
                isResearchDisabled() ||
                !hasAllRequirements("research", selectedResearchId, nextLvl) ||
                (isSomeResearchInProcess && !isCurrentResearchInProcess)
              }
              onClick={() => {
                run(selectedResearchId);
              }}
            >
              {!requiredResources?.length ? "Max Level" : "Research"}
            </button>
          )}

          {isCurrentResearchInProcess && (
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
  min-height: 300px;
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;
