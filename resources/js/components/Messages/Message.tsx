import React, { useEffect, useState } from "react";
import { SContent, SH1, SParam, SParams } from "../styles";
import { httpClient } from "../../httpClient/httpClient";
import { Link, NavLink, useParams } from "react-router-dom";
import { IMessage } from "./types";
import { Icon } from "../Common/Icon";

export const Message = ({}) => {
  const [message, setMessage] = useState<IMessage>();
  const [isLoading, setIsLoading] = useState(false);

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
      setIsLoading(false);
    });
  };

  return (
    <SContent>
      <NavLink to={"/messages"}>Back to Messages</NavLink>
      <SH1>Message</SH1>

      {message && (
        <>
          <div className={"row"}>
            <div className={"col-4"}>Date</div>
          </div>

          <div className={"row"}>
            <div className={"col-4"}>{message.createdAt}</div>
          </div>

          <div className={"row"}>
            <div className={"col-12"}>{message.content}</div>
          </div>

          {message.eventType === "Battle" && (
            <div className={"row"}>
              <div className={"col-12"}>
                <Link to={"/logs/" + message.battleLogId}>Battle Log</Link>
              </div>
            </div>
          )}

          {message.eventType === "Fleet" && (
            <div className={"row"}>
              <div className={"col-12"}>
                Fleet brought
                <SParams>
                  {message.gold > 0 && (
                    <SParam>
                      <Icon title={"gold"} />
                      {message.gold}
                    </SParam>
                  )}
                  {message.population > 0 && (
                    <SParam>
                      <Icon title={"worker"} />
                      {message.population}
                    </SParam>
                  )}
                </SParams>
              </div>
            </div>
          )}
        </>
      )}
    </SContent>
  );
};
