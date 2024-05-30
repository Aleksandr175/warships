import React, { useEffect, useRef } from "react";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import customParseFormat from "dayjs/plugin/customParseFormat";
import { SH2 } from "../styles";
import styled from "styled-components";
import { convertSecondsToTime, getTimeLeft } from "../../utils";
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

  const { warshipQueue, warshipSlots, updateCityWarshipsData } =
    useCityWarships({ cityId });

  const timer = useRef();

  useEffect(() => {
    // @ts-ignore
    timer.current = setInterval(handleTimer, 1000);

    return () => {
      clearInterval(timer.current);
    };
  }, []);

  function handleTimer() {
    const queue = warshipQueue;

    const newQueue = queue?.map((item) => {
      if (item.time > 0) {
        item.time -= 1;
      }

      return item;
    });

    updateCityWarshipsData({
      cityId,
      warshipQueue: newQueue || [],
    });
  }

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
          const time = getTimeLeft(item.deadline);

          return (
            <div key={item.warshipId + "-" + time}>
              <SCell>
                <SWarshipIcon
                  style={{
                    backgroundImage: `url("../images/warships/simple/dark/${item.warshipId}.svg")`,
                  }}
                />
                {getWarshipName(item.warshipId)}
              </SCell>
              <SCell>{item.qty}</SCell>

              {/* TODO: fix time */}

              <SCell>{convertSecondsToTime(getTimeLeft(item.deadline))}</SCell>
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
