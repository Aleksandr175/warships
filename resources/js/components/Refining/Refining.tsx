import React from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { SContent, SH1, SH2 } from "../styles";
import styled, { css } from "styled-components";
import { Icon } from "../Common/Icon";
import { ProgressBar } from "../Common/ProgressBar";

export const Refining = () => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  // TODO: get recipes

  console.log("Refining");

  if (!dictionaries) {
    return null;
  }

  return (
    <SContent>
      <SH1>Refining Resources</SH1>
      <SRefiningSlots>
        <SRefiningSlot empty={false}>
          <SRefiningMaterials>
            <SRefiningMaterialSlot>
              <Icon title={"log"} size={"big"} />
            </SRefiningMaterialSlot>
            <SRefiningMaterialTitle>120</SRefiningMaterialTitle>
            <SRefiningMaterialSlot>
              <Icon title={"plank"} size={"big"} />
            </SRefiningMaterialSlot>
          </SRefiningMaterials>

          <SProgressWrapper>
            <ProgressBar percent={31} />
          </SProgressWrapper>
        </SRefiningSlot>

        <SRefiningSlot empty={false}>
          <SRefiningMaterials>
            <SRefiningMaterialSlot>
              <Icon title={"plank"} size={"big"} />
            </SRefiningMaterialSlot>
            <SRefiningMaterialTitle>55</SRefiningMaterialTitle>
            <SRefiningMaterialSlot>
              <Icon title={"lumber"} size={"big"} />
            </SRefiningMaterialSlot>
          </SRefiningMaterials>

          <SProgressWrapper>
            <ProgressBar percent={100} />
          </SProgressWrapper>
        </SRefiningSlot>

        <SRefiningSlot empty={false}>
          <SRefiningMaterials>
            <SRefiningMaterialSlot>
              <Icon title={"ore"} size={"big"} />
            </SRefiningMaterialSlot>
            <SRefiningMaterialTitle>999</SRefiningMaterialTitle>
            <SRefiningMaterialSlot>
              <Icon title={"iron"} size={"big"} />
            </SRefiningMaterialSlot>
          </SRefiningMaterials>

          <SProgressWrapper>
            <ProgressBar percent={0} />
          </SProgressWrapper>
        </SRefiningSlot>

        <SRefiningSlot empty={true}>
          <SRefiningMaterials>
            <SRefiningMaterialTitle>Free Slot</SRefiningMaterialTitle>
          </SRefiningMaterials>
        </SRefiningSlot>
      </SRefiningSlots>

      <SH2>Refining Recipes</SH2>
    </SContent>
  );
};

const SRefiningSlots = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 40px;
`;

const SRefiningSlot = styled.div<{ empty?: boolean }>`
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

const SRefiningMaterials = styled.div`
  display: flex;
  margin-top: 10px;
  padding: 0 25px;
  justify-content: space-between;
  align-items: center;
`;

const SRefiningMaterialSlot = styled.div`
  width: 32px;
  height: 32px;
  border-radius: var(--block-border-radius);
  background: #e7ecfd;
`;

const SRefiningMaterialTitle = styled.div`
  color: white;
  font-size: 20px;
  width: 100%;
  text-align: center;
`;

const SProgressWrapper = styled.div`
  position: absolute;
  bottom: 10px;
  left: 10px;
  right: 10px;
`;
