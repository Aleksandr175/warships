import React from "react";
import styled from "styled-components";

export const MapAction = ({
  title,
  description,
  logoUrl,
  onClick,
  logoStyle = {},
}: {
  title: string;
  description: string;
  logoUrl: string;
  onClick: () => void;
  logoStyle?: React.CSSProperties;
}) => (
  <SColumn>
    <SMapAction>
      <SMapActionLogo
        style={{ backgroundImage: `url(${logoUrl})`, ...logoStyle }}
      />
      <SMapActionDescription>
        <strong>{title}</strong>
        <p>{description}</p>
        <button className={"btn btn-primary"} onClick={onClick}>
          {title}
        </button>
      </SMapActionDescription>
    </SMapAction>
  </SColumn>
);

const SColumn = styled.div`
  width: 33%;
`;

const SMapAction = styled.div`
  display: flex;
  gap: 10px;
`;

const SMapActionLogo = styled.div`
  width: 100px;
  height: 100px;
  min-width: 100px;
  background-size: contain;
  background-repeat: no-repeat;
`;

const SMapActionDescription = styled.div`
  p {
    font-size: 12px;
    color: #949494;
  }
`;
