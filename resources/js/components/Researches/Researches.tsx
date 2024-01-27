import React, { useEffect, useRef, useState } from "react";
import {
  IBuilding,
  ICityResearchQueue,
  ICityResource,
  IResearch,
  IResearchDependency,
  IResearchResource,
  IResourceDictionary,
  IUserResearch,
} from "../../types/types";
import { Research } from "./Research";
import { SContent, SH1 } from "../styles";
import { getTimeLeft } from "../../utils";
import styled from "styled-components";
import { SelectedResearch } from "./SelectedResearch";

interface IProps {
  cityId: number;
  dictionary: IResearch[];
  researchResourcesDictionary: IResearchResource[];
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  researches: IUserResearch[];
  queue?: ICityResearchQueue;
  setQueue: (q: ICityResearchQueue | undefined) => void;
  getResearches: () => void;
  researchDependencyDictionary: IResearchDependency[];
  buildingsDictionary: IBuilding[];
  resourcesDictionary: IResourceDictionary[];
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
  researchDependencyDictionary,
  buildingsDictionary,
  resourcesDictionary,
}: IProps) => {
  const [selectedResearchId, setSelectedResearchId] = useState(0);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();

  useEffect(() => {
    setSelectedResearchId(dictionary[0]?.id || 0);
  }, [dictionary]);

  function getLvl(researchId: number) {
    const research = researches?.find((r) => r.researchId === researchId);

    if (research) {
      return research.lvl;
    }

    return 0;
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
    <SContent>
      <SH1>Researches</SH1>
      {selectedResearchId && (
        <SelectedResearch
          selectedResearchId={selectedResearchId}
          researches={researches}
          cityId={cityId}
          researchesDictionary={dictionary}
          researchResourcesDictionary={researchResourcesDictionary}
          researchDependencyDictionary={researchDependencyDictionary}
          updateCityResources={updateCityResources}
          cityResources={cityResources}
          queue={queue}
          setQueue={setQueue}
          timeLeft={timeLeft}
          getLvl={getLvl}
          buildingsDictionary={buildingsDictionary}
          resourcesDictionary={resourcesDictionary}
        />
      )}

      {dictionary.map((item) => {
        const lvl = getLvl(item.id);

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
              timeLeft={
                queue?.researchId === item.id
                  ? getTimeLeft(queue?.deadline || "")
                  : 0
              }
              selected={selectedResearchId === item.id}
            />
          </SItemWrapper>
        );
      })}
    </SContent>
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
