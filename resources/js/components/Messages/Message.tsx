import React from "react";
import { SContent, SH1 } from "../styles";
import { NavLink, useParams } from "react-router-dom";
import { IMessage } from "./types";
import { Icon } from "../Common/Icon";
import { FleetWarships } from "../Common/FleetWarships";
import { formatDate, getResourceSlug } from "../../utils";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import styled from "styled-components";
import { SDate, SMessageCity, SMessageHeader } from "./styles";
import { MessageBattleLog } from "./MessageBattleLog";
import { useFetchMessage } from "../../hooks/useFetchMessage";

export const Message = ({ userId }: { userId: number }) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  let params = useParams<{ id: string }>();

  const { data, isPending: isLoading } = useFetchMessage(Number(params.id));

  const message = data?.message;

  const getMessageHeader = (messageItem: IMessage) => {
    let title = dictionaries?.messageTemplates?.find(
      (template) => template.templateId === messageItem?.templateId
    )?.title;

    return title || "";
  };

  const getMessageContent = (messageItem: IMessage) => {
    let content = dictionaries?.messageTemplates?.find(
      (template) => template.templateId === messageItem?.templateId
    )?.content;

    return content || "";
  };

  const city = data?.cities?.find((city) => city.id === message?.targetCityId);

  if (!dictionaries || !message) {
    return <></>;
  }

  return (
    <SContent>
      <NavLink to={"/messages"}>Back to Messages</NavLink>
      {isLoading && <>Loading...</>}

      {message && (
        <>
          <SMessageHeader>
            <SH1>
              {getMessageHeader(message)}{" "}
              {city && (
                <SMessageCity>
                  ({city?.title} [{city?.coordX}:{city?.coordY}])
                </SMessageCity>
              )}
            </SH1>
            <SDate>{formatDate(message?.createdAt || "")}</SDate>
          </SMessageHeader>

          <SMessage>
            <div>{getMessageContent(message)}</div>

            {message.fleetDetails && message.fleetDetails.length > 0 && (
              <div>
                <div>
                  <strong>Fleet</strong>
                </div>
                <div>
                  <FleetWarships warships={message.fleetDetails} />
                </div>
              </div>
            )}

            {message.resources && message.resources.length > 0 && (
              <div>
                <div>
                  <strong>Fleet has resources</strong>
                </div>
                <SResources>
                  {message.resources.map((resource) => {
                    return (
                      <SResource key={resource.resourceId}>
                        <Icon
                          title={getResourceSlug(
                            dictionaries.resourcesDictionary,
                            resource.resourceId
                          )}
                        />
                        {Math.floor(resource?.qty || 0)}
                      </SResource>
                    );
                  })}
                </SResources>
              </div>
            )}

            {message.battleLog && (
              <MessageBattleLog
                userId={userId}
                message={data.message}
                cities={data.cities}
                battleLog={message.battleLog}
                battleLogDetails={message.battleLogDetails}
              />
            )}
          </SMessage>
        </>
      )}
    </SContent>
  );
};

const SResources = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 10px 20px;
`;

const SResource = styled.div`
  display: flex;
  gap: 5px;
`;

const SMessage = styled.div`
  display: flex;
  flex-direction: column;
  gap: 20px;
`;
