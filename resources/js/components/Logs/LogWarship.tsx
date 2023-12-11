import React from "react";
import styled from "styled-components";
import { IBattleLogDetail } from "./Log";

export const LogWarship = ({
  data,
  mode = "light",
}: {
  data: IBattleLogDetail;
  mode?: "light" | "dark";
}) => {
  return (
    <SWrapper>
      <SWarshipIcon
        style={{
          backgroundImage: `url("../images/warships/simple/${mode}/${data.warshipId}.svg")`,
        }}
      />
      {data.qty}
    </SWrapper>
  );
};

const SWrapper = styled.div`
  display: flex;
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
