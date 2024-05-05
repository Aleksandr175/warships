import React from "react";
import styled from "styled-components";
import { SBadge } from "../Common/styles";

export const BattleLogWarship = ({
  data,
  mode = "light",
}: {
  data: {
    warshipId: number;
    qty: number;
  };
  mode?: "light" | "dark";
}) => {
  return (
    <SWrapper>
      <SWarshipIcon
        style={{
          backgroundImage: `url("../images/warships/simple/${mode}/${data.warshipId}.svg")`,
        }}
      />
      <SBadge>{data.qty}</SBadge>
    </SWrapper>
  );
};

const SWrapper = styled.div`
  display: inline-flex;
  align-items: center;
`;

const SWarshipIcon = styled.div`
  display: inline-block;
  background-size: contain;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  margin-right: 10px;

  width: 40px;
  height: 24px;
`;
