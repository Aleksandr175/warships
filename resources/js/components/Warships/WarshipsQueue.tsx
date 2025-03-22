import React, { useEffect, useRef, useState } from "react";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { SH2 } from "../styles";
import styled from "styled-components";
import { convertSecondsToTime } from "../../utils";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { useCityWarships } from "../hooks/useCityWarships";

dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  cityId: number;
}

export const WarshipsQueue = ({ cityId }: IProps) => {
  const queryDictionaries = useFetchDictionaries();
  const dictionaries = queryDictionaries.data;
  const { warshipQueue, warshipSlots } = useCityWarships({ cityId });
  const [, setForceUpdate] = useState(0);
  const timer = useRef<NodeJS.Timeout>();

  useEffect(() => {
    // Set up timer for countdown
    timer.current = setInterval(() => {
      setForceUpdate((prev) => prev + 1);
    }, 1000);

    return () => {
      if (timer.current) {
        clearInterval(timer.current);
      }
    };
  }, []);

  function getWarshipName(warshipId: number): string | undefined {
    return dictionaries?.warshipsDictionary.find(
      (warship) => warship.id === warshipId
    )?.title;
  }

  return (
    <>
      <SH2>
        Warships Queue ({warshipQueue?.length} / {warshipSlots})
      </SH2>
      <STable>
        <div>
          <SCellHeader>Warship</SCellHeader>
          <SCellHeader>Qty</SCellHeader>
          <SCellHeader>Time Left</SCellHeader>
          <SCellHeader>Deadline</SCellHeader>
        </div>

        {warshipQueue?.map((item) => {
          const deadline = dayjs.utc(item.deadline);
          const timeLeft = deadline.diff(dayjs(), "second");
          const localDeadline = deadline.local();

          return (
            <div key={item.warshipId + "-" + timeLeft}>
              <SCell>
                <SWarshipIcon
                  style={{
                    backgroundImage: `url("../images/warships/simple/dark/${item.warshipId}.svg")`,
                  }}
                />
                {getWarshipName(item.warshipId)}
              </SCell>
              <SCell>{item.qty}</SCell>
              <SCell>{convertSecondsToTime(timeLeft)}</SCell>
              <SCell>{localDeadline.format("DD MMM, YYYY HH:mm:ss")}</SCell>
            </div>
          );
        })}
      </STable>
    </>
  );
};

const SWarshipIcon = styled.div`
  display: inline-block;
  background-size: contain;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  margin-right: 10px;

  width: 28px;
  height: 24px;
`;

const SCellHeader = styled.div`
  display: flex;
  align-items: center;
  color: #949494;
  width: 25%;
`;

const SCell = styled.div`
  display: flex;
  gap: 10px;
  width: 25%;
  align-items: center;
`;

const STable = styled.div`
  padding-top: 10px;
  font-size: 12px;

  > div {
    display: flex;
    width: 100%;
    gap: 10px;
    padding-bottom: 20px;
  }
`;
