import React from "react";
import { IFleetWarshipsData } from "../../types/types";
import styled from "styled-components";

export const FleetWarships = ({
  warships,
}: {
  warships: IFleetWarshipsData[];
}) => {
  return (
    <SFleetDetails>
      {warships.map((fWarships) => {
        return (
          <SFleetDetail key={fWarships.warshipId}>
            <SWarshipIcon
              style={{
                backgroundImage: `url("../images/warships/simple/dark/${fWarships.warshipId}.svg")`,
              }}
            />
            <span>{fWarships.qty}</span>
          </SFleetDetail>
        );
      })}
    </SFleetDetails>
  );
};

const SFleetDetails = styled.div`
  display: flex;
  font-size: 12px;
`;

const SWarshipIcon = styled.div`
  display: inline-block;
  background-size: contain;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  margin-right: 10px;

  width: 20px;
  height: 15px;
`;

const SFleetDetail = styled.div`
  display: flex;
  align-items: center;
  margin-right: 10px;
  font-weight: bold;
`;
