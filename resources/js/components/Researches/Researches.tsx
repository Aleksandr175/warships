import React, { useEffect, useRef, useState } from "react";
import { httpClient } from "../../httpClient/httpClient";
import {
  ICityResearchQueue,
  ICityResources,
  IResearch,
  IResearchResource,
  IUserResearch,
} from "../../types/types";
import { Research } from "./Research";
import { SH1, SH2, SText } from "../styles";
import { Card } from "../Common/Card";
import { Icon } from "../Common/Icon";
import { convertSecondsToTime, getTimeLeft } from "../../utils";
import styled from "styled-components";

interface IProps {
  cityId: number;
  dictionary: IResearch[];
  researchResourcesDictionary: IResearchResource[];
  updateCityResources: (cityResources: ICityResources) => void;
  cityResources: ICityResources;
  researches: IUserResearch[];
  queue?: ICityResearchQueue;
  setQueue: (q: ICityResearchQueue | undefined) => void;
  getResearches: () => void;
  /*setBuildings: (buildings: ICityBuilding[]) => void;
   */
}

export const Researches = ({
  cityId,
  dictionary,
  researchResourcesDictionary,
  updateCityResources,
  cityResources,
  researches,
  queue,
  setQueue,
  getResearches,
}: IProps) => {
  const [selectedResearchId, setSelectedResearchId] = useState(0);
  const selectedResearch = getResearch(selectedResearchId);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();
  const lvl = getLvl(selectedResearchId);

  useEffect(() => {
    setSelectedResearchId(dictionary[0]?.id || 0);
  }, [dictionary]);

  function getResearch(researchId: number): IResearch | undefined {
    return dictionary.find((research) => research.id === researchId);
  }

  function getLvl(researchId: number) {
    const research = researches?.find((r) => r.researchId === researchId);

    if (research) {
      return research.lvl;
    }

    return 0;
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

  useEffect(() => {
    if (getTimeLeft(queue?.deadline || "")) {
      setTimeLeft(getTimeLeft(queue?.deadline || ""));

      // @ts-ignore
      timer.current = setInterval(handleTimer, 1000);

      return () => {
        clearInterval(timer.current);
      };
    } else {
      setTimeLeft(0);
    }
  }, [queue, selectedResearchId]);

  useEffect(() => {
    // TODO strange decision
    if (timeLeft === -1) {
      clearInterval(timer.current);
      getResearches();
    }
  }, [timeLeft]);

  function handleTimer() {
    setTimeLeft((lastTimeLeft) => {
      // @ts-ignore
      return lastTimeLeft - 1;
    });
  }

  return (
    <>
      <SH1>Researches</SH1>
      {selectedResearchId && selectedResearch && (
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
                  <Icon title={"gold"} /> {gold}
                  <Icon title={"worker"} /> {population}
                  <Icon title={"time"} /> {convertSecondsToTime(time)}
                </>
              )}
            </div>
            <br />
            {!isResearchInProcess() && (
              <button
                className={"btn btn-primary"}
                disabled={isResearchDisabled()}
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
            <br />
            <br />
            <SText>{selectedResearch?.description}</SText>
          </div>
        </SSelectedItem>
      )}

      {dictionary.map((item) => {
        const lvl = getLvl(item.id);
        const researchResources = getResourcesForResearch(item.id, lvl + 1);
        const gold = researchResources?.gold || 0;
        const population = researchResources?.population || 0;

        return (
          <SItemWrapper
            onClick={() => {
              setSelectedResearchId(item.id);
            }}
          >
            <Research
              lvl={lvl}
              key={item.id}
              research={item}
              gold={gold}
              population={population}
              run={run}
              cancel={cancel}
              queue={queue}
              timeLeft={
                queue?.researchId === item.id
                  ? getTimeLeft(queue?.deadline || "")
                  : 0
              }
              getResearches={getResearches}
              cityResources={cityResources}
              selected={selectedResearchId === item.id}
            />
          </SItemWrapper>
        );
      })}
    </>
  );
};

const SItemWrapper = styled.div`
  display: inline-block;
`;

const SSelectedItem = styled.div`
  margin-bottom: calc(var(--block-gutter-y) * 2);
`;

const SCardWrapper = styled.div`
  height: 120px;
  border-radius: var(--block-border-radius-small);
  overflow: hidden;
`;
