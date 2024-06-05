import React, { useEffect, useRef, useState } from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import styled, { css } from "styled-components";
import { Icon } from "../Common/Icon";
import { ProgressBar } from "../Common/ProgressBar";
import { ICity, IRefiningQueue } from "../../types/types";
import { getResourceSlug, getTimeLeft } from "../../utils";

export const RefiningSlot = ({ data }: { data: IRefiningQueue }) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const [timeLeft, setTimeLeft] = useState<number>(0);
  const timer = useRef();

  useEffect(() => {
    if (getTimeLeft(data?.deadline || "")) {
      setTimeLeft(getTimeLeft(data?.deadline || ""));

      // @ts-ignore
      timer.current = setInterval(handleTimer, 1000);

      return () => {
        clearInterval(timer.current);
      };
    } else {
      setTimeLeft(0);
    }
  }, [data]);

  const handleTimer = () => {
    setTimeLeft((lastTimeLeft) => {
      if (lastTimeLeft < 1) {
        return 0;
      }

      return lastTimeLeft - 1;
    });
  };

  return (
    <SRefiningSlot empty={false}>
      <SRefiningMaterials>
        {dictionaries?.resourcesDictionary && (
          <SRefiningMaterialSlot>
            <Icon
              title={getResourceSlug(
                dictionaries?.resourcesDictionary,
                data.inputResourceId
              )}
              size={"big"}
            />
          </SRefiningMaterialSlot>
        )}
        <SRefiningMaterialTitle>{data.outputQty}</SRefiningMaterialTitle>
        {dictionaries?.resourcesDictionary && (
          <SRefiningMaterialSlot>
            <Icon
              title={getResourceSlug(
                dictionaries?.resourcesDictionary,
                data.outputResourceId
              )}
              size={"big"}
            />
          </SRefiningMaterialSlot>
        )}
      </SRefiningMaterials>

      <SProgressWrapper>
        <ProgressBar
          percent={Math.ceil(((data.time - timeLeft) / data.time) * 100)}
        />
      </SProgressWrapper>
    </SRefiningSlot>
  );
};

export const SRefiningSlot = styled.div<{ empty?: boolean }>`
  position: relative;
  width: 160px;
  height: 160px;
  border-radius: var(--block-border-radius);
  background: rgb(0, 0, 0);
  background: linear-gradient(
    0deg,
    rgba(160, 205, 245, 1) 0%,
    rgba(42, 97, 244, 1) 100%
  );

  &:after {
    display: block;
    position: absolute;
    content: "";
    width: 100px;
    height: 100px;
    top: 50%;
    left: 50%;
    margin-top: -40px;
    margin-left: -50px;

    ${({ empty }) =>
      empty
        ? css`
            background: url("../../../images/icons/pot-empty.svg") no-repeat;
            background-size: contain;
          `
        : css`
            background: url("../../../images/icons/pot.svg") no-repeat;
            background-size: contain;
          `};
  }
`;

export const SRefiningMaterials = styled.div`
  display: flex;
  margin-top: 10px;
  padding: 0 25px;
  justify-content: space-between;
  align-items: center;
`;

export const SRefiningMaterialSlot = styled.div`
  width: 32px;
  height: 32px;
  border-radius: var(--block-border-radius);
  background: #e7ecfd;
`;

export const SRefiningMaterialTitle = styled.div`
  color: white;
  font-size: 20px;
  width: 100%;
  text-align: center;
`;

export const SProgressWrapper = styled.div`
  position: absolute;
  bottom: 10px;
  left: 10px;
  right: 10px;
`;
