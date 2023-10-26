import React, { useEffect, useState } from "react";
import { SContent, SH1 } from "../styles";
import ReactPaginate from "react-paginate";
import { httpClient } from "../../httpClient/httpClient";
import dayjs from "dayjs";
import { NavLink } from "react-router-dom";
import { IMessage } from "./types";

export const Messages = ({}) => {
  const [messages, setMessages] = useState<IMessage[]>([]);
  const [messagesNumber, setMessagesNumber] = useState(0);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    // get messages
    getMessages(1);
  }, []);

  const getMessages = (page: number) => {
    setIsLoading(true);

    httpClient.get("/messages?page=" + page).then((resp) => {
      console.log(resp.data);
      setMessages(resp.data.messages);
      setMessagesNumber(resp.data.messagesNumber);
      setIsLoading(false);
    });
  };

  return (
    <SContent>
      <SH1>Messages</SH1>

      {!messages?.length && <>No messages yet</>}

      {messages.map((message) => {
        return (
          <div key={message.id}>
            <div className={"row"}>
              <div className={"col-6"}>
                {!message.isRead ? "New!" : ""}{" "}
                {dayjs(message.createdAt).format("DD MMM, YYYY HH:mm:ss")}
              </div>
            </div>

            <div className={"row"}>
              <div className={"col-12"}>{message.content}</div>
            </div>

            <NavLink to={"/messages/" + message.id}>Show</NavLink>

            <hr />
          </div>
        );
      })}

      {messagesNumber > 0 && (
        <ReactPaginate
          breakLabel="..."
          nextLabel="next >"
          onPageChange={(page) => {
            getMessages(page.selected + 1);
            console.log("change page");
          }}
          pageRangeDisplayed={10}
          pageCount={Math.ceil(messagesNumber / 10)}
          previousLabel="< previous"
          containerClassName="pagination"
          activeClassName="active"
          pageClassName="page-item"
          pageLinkClassName="page-link"
          previousClassName="page-item"
          previousLinkClassName="page-link"
          nextClassName="page-item"
          nextLinkClassName="page-link"
          breakClassName="page-item"
          breakLinkClassName="page-link"
        />
      )}
    </SContent>
  );
};
