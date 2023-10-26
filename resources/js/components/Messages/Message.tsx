import React, { useEffect, useState } from "react";
import { SContent, SH1 } from "../styles";
import { httpClient } from "../../httpClient/httpClient";
import { NavLink, useParams } from "react-router-dom";
import { IMessage } from "./types";

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
        </>
      )}
    </SContent>
  );
};
