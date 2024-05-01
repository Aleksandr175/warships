import React, { useEffect, useState } from "react";
import { SContent, SH1 } from "../styles";
import { httpClient } from "../../httpClient/httpClient";
import { Link, NavLink, useParams } from "react-router-dom";
import { IMessage } from "./types";
import { Icon } from "../Common/Icon";
import { FleetWarships } from "../Common/FleetWarships";
import { formatDate, getResourceSlug } from "../../utils";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import styled from "styled-components";
import { ICityShort } from "../../types/types";
import { SDate, SMessageCity, SMessageHeader } from "./styles";

export const Message = () => {
  const [message, setMessage] = useState<IMessage>();
  const [cities, setCities] = useState<ICityShort[]>();
  const [isLoading, setIsLoading] = useState(false);

  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  let params = useParams<{ id: string }>();

  useEffect(() => {
    // get messages
    getMessage(Number(params.id));
  }, []);

  const getMessage = (messageId: number) => {
    setIsLoading(true);

    httpClient.get("/messages/" + messageId).then((resp) => {
      console.log(resp.data);
      setMessage(resp.data.message);
      setCities(resp.data.cities);
      setIsLoading(false);
    });
  };

  const getMessageHeader = (messageItem: IMessage) => {
    let title = dictionaries?.messageTemplates?.find(
      (template) => template.templateId === messageItem?.templateId
    )?.title;

    return title || "";
  };

  const city = cities?.find((city) => city.id === message?.targetCityId);

  if (!dictionaries || !message) {
    return <></>;
  }

  return (
    <SContent>
      <NavLink to={"/messages"}>Back to Messages</NavLink>
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
        <div>{message.content}</div>

        {message.eventType === "Battle" && (
          <div className={"row"}>
            <div className={"col-12"}>
              <Link to={"/logs/" + message.battleLogId}>Battle Log</Link>
            </div>
          </div>
        )}

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
              <strong>Fleet delivered resources</strong>
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
      </SMessage>
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
