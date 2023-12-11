import React, { useEffect, useRef, useState } from "react";
import { ICityWarshipQueue, IWarship } from "../../types/types";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { SH2 } from "../styles";
import styled from "styled-components";
import { convertSecondsToTime, getTimeLeft } from "../../utils";
dayjs.extend(utc);
dayjs.extend(customParseFormat);

interface IProps {
  dictionary: IWarship[];
  queue?: ICityWarshipQueue[];
  sync: () => void;
}

export const WarshipsQueue = ({ dictionary, queue, sync }: IProps) => {
  const timer = useRef();
  const [tempQueue, setTempQueue] = useState(queue || []);

  useEffect(() => {
    setTempQueue(queue || []);

    // @ts-ignore
    timer.current = setInterval(handleTimer, 1000);

    return () => {
      clearInterval(timer.current);
    };
  }, [queue]);

  function handleTimer() {
    const q: ICityWarshipQueue[] = tempQueue;

    const newQ = q?.map((item) => {
      if (item.time > 0) {
        item.time -= 1;
      }

      if (item.time === 0) {
        sync();
      }

      return item;
    });

    setTempQueue(newQ);
  }

  function getWarshipName(warshipId: number): string | undefined {
    return dictionary.find((warship) => warship.id === warshipId)?.title;
  }

  return (
    <>
      <SH2>Warships Queue</SH2>
      <STable>
        <div>
          <SCellHeader>Warship</SCellHeader>
          <SCellHeader>Qty</SCellHeader>
          <SCellHeader>Time Left</SCellHeader>
          <SCellHeader>Deadline</SCellHeader>
        </div>

        {queue?.map((item) => {
          const time = getTimeLeft(item.deadline);

          return (
            <div>
              <SCell>
                <SWarshipIcon
                  style={{
                    backgroundImage: `url("../images/warships/simple/dark/${item.warshipId}.svg")`,
                  }}
                />
                {getWarshipName(item.warshipId)}
              </SCell>
              <SCell>{item.qty}</SCell>
              <SCell>{convertSecondsToTime(time > 0 ? time : 0)}</SCell>
              <SCell>
                {dayjs(item.deadline).format("DD MMM, YYYY HH:mm:ss")}
              </SCell>
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
