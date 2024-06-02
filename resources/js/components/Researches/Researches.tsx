import React, { useEffect, useRef, useState } from "react";
import { Research } from "./Research";
import { SContent, SH1 } from "../styles";
import { getTimeLeft } from "../../utils";
import styled from "styled-components";
import { SelectedResearch } from "./SelectedResearch";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useResearches } from "../hooks/useResearches";

interface IProps {
  cityId: number;
}

export const Researches = ({ cityId }: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const { researchQueue, researches } = useResearches({
    cityId,
  });

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
    if (getTimeLeft(researchQueue?.deadline || "")) {
      setTimeLeft(getTimeLeft(researchQueue?.deadline || ""));

      // @ts-ignore
      timer.current = setInterval(handleTimer, 1000);

      return () => {
        clearInterval(timer.current);
      };
    } else {
      setTimeLeft(0);
    }
  }, [researchQueue, selectedResearchId]);

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
          cityId={cityId}
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
                researchQueue?.researchId === item.id
                  ? getTimeLeft(researchQueue?.deadline || "")
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
