import styled from "styled-components";
import { SBadge } from "../Common/styles";

export const SDate = styled.div`
  font-size: 11px;
`;

export const SMessageHeader = styled.div`
  display: flex;
  justify-content: space-between;
`;

export const SMessageCity = styled.span`
  font-weight: 400;
  padding-left: 5px;
`;

export const SMessageBadge = styled(SBadge)`
  margin-right: 5px;
`;
