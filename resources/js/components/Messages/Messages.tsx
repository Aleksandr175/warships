import React, { useState } from "react";
import { SContent, SH1 } from "../styles";
import ReactPaginate from "react-paginate";
import { NavLink } from "react-router-dom";
import { IMessage } from "./types";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { formatDate } from "../../utils";
import { SDate, SMessageBadge, SMessageCity, SMessageHeader } from "./styles";
import { useFetchMessages } from "../../hooks/useFetchMessages";

export const Messages = ({}) => {
  const [page, setPage] = useState(1);

  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const { data, isPending: isLoading } = useFetchMessages(page);

  const messages = data?.messages || [];
  const messagesNumber = data?.messagesNumber || 0;
  const cities = data?.cities || [];

  const getMessageHeader = (messageItem: IMessage) => {
    let title = dictionaries?.messageTemplates?.find(
      (template) => template.templateId === messageItem?.templateId
    )?.title;

    return title || "";
  };

  const getCity = (cityId: number) => {
    return cities?.find((city) => city.id === cityId);
  };

  return (
    <SContent>
      <SH1>Messages</SH1>

      {isLoading && <>Loading...</>}

      {!messages?.length && !isLoading && <>No messages yet</>}

      {messages?.map((message) => {
        const city = getCity(message.cityId || 0);

        return (
          <div key={message.id}>
            <SMessageHeader>
              <SH1>
                {!message.isRead && <SMessageBadge>New</SMessageBadge>}
                {getMessageHeader(message)}
                {city && (
                  <SMessageCity>
                    ({city?.title} [{city?.coordX}:{city?.coordY}])
                  </SMessageCity>
                )}
              </SH1>
              <SDate>{formatDate(message?.createdAt || "")}</SDate>
            </SMessageHeader>

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
            setPage(page.selected + 1);
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
