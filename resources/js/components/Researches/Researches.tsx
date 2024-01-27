import React, { useEffect, useRef, useState } from "react";
import {
  ICityResearchQueue,
  ICityResource,
  IUserResearch,
} from "../../types/types";
import { Research } from "./Research";
import { SContent, SH1 } from "../styles";
import { getTimeLeft } from "../../utils";
import styled from "styled-components";
import { SelectedResearch } from "./SelectedResearch";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";

interface IProps {
  cityId: number;
  updateCityResources: (cityResources: ICityResource[]) => void;
  cityResources: ICityResource[];
  researches: IUserResearch[];
  queue?: ICityResearchQueue;
  setQueue: (q: ICityResearchQueue | undefined) => void;
  getResearches: () => void;
}

export const Researches = ({
  cityId,
  updateCityResources,
  cityResources,
  researches,
  queue,
  setQueue,
  getResearches,
}: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const [selectedResearchId, setSelectedResearchId] = useState(0);
  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();

  useEffect(() => {
    if (dictionaries) {
      setSelectedResearchId(dictionaries.researches[0]?.id || 0);
    }
  }, [dictionaries]);

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

  if (!dictionaries) {
    return null;
  }

  return (
    <SContent>
      <SH1>Researches</SH1>
      {selectedResearchId && (
        <SelectedResearch
          selectedResearchId={selectedResearchId}
          researches={researches}
          cityId={cityId}
          updateCityResources={updateCityResources}
          cityResources={cityResources}
          queue={queue}
          setQueue={setQueue}
          timeLeft={timeLeft}
          getLvl={getLvl}
        />
      )}

      {dictionaries.researches.map((item) => {
        const lvl = getLvl(item.id);

        return (
          <SItemWrapper
            key={item.id}
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
