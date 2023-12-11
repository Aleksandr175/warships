import React, { useEffect, useState } from "react";
import { IMapCity, IWarship } from "../../types/types";
import { httpClient } from "../../httpClient/httpClient";
import ReactPaginate from "react-paginate";
import { NavLink } from "react-router-dom";
import { SContent } from "../styles";
import dayjs from "dayjs";

interface IProps {
  dictionary: IWarship[];
  userId: number;
}

interface IBattleLog {
  battleLogId: number;
  attackerUserId: number;
  defenderUserId: number;
  winner: "attacker" | "defender";
  date: string;
  cityId: number;
}

interface IBattleLogDetail {
  battleLogId: number;
  round: number;
  warshipId: number;
  qty: number;
  destroyed: number;
}

export const Logs = ({ dictionary, userId }: IProps) => {
  const [logs, setLogs] = useState<IBattleLog[]>([]);
  const [cities, setCities] = useState<IMapCity[]>([]);
  const [logsDetails, setLogsDetails] = useState<IBattleLogDetail[]>([]);
  const [logsCount, setLogsCount] = useState(0);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    getLogs(1);
  }, []);

  const getLogs = (page: number) => {
    setIsLoading(true);

    httpClient.get("/battle-logs?page=" + page).then((resp) => {
      console.log(resp.data);
      setCities(resp.data.cities);
      setLogs(resp.data.battleLogs);
      setLogsDetails(resp.data.battleLogsDetails);
      setLogsCount(resp.data.battleLogsCount);
      setIsLoading(false);
    });
  };

  const getCityName = (cityId: number): string => {
    return cities.find((city) => city.id === cityId)?.title || "";
  };

  return (
    <SContent>
      {!logs?.length && <>No logs yet</>}

      {logs?.length > 0 && (
        <>
          <div className={"row"}>
            <div className={"col-3"}>Date</div>
            <div className={"col-3"}>City</div>
            <div className={"col-3"}>Type</div>
            <div className={"col-3"}>Result</div>
          </div>

          <hr />
        </>
      )}

      {logs.map((log) => {
        return (
          <div key={log.battleLogId}>
            <div className={"row"}>
              <div className={"col-3"}>
                {dayjs(log.date).format("DD MMM, YYYY HH:mm:ss")}
              </div>
              <div className={"col-3"}>{getCityName(log.cityId)}</div>
              <div className={"col-3"}>
                {log.attackerUserId === userId ? "Attack" : "Defend"}
              </div>
              <div className={"col-3"}>
                {log.winner === "attacker" ? "Victory" : "Defeat"}
              </div>
            </div>

            <NavLink to={"/logs/" + log.battleLogId}>Show</NavLink>

            <hr />
          </div>
        );
      })}

      {logsCount > 0 && (
        <ReactPaginate
          breakLabel="..."
          nextLabel="next >"
          onPageChange={(page) => {
            getLogs(page.selected + 1);
            console.log("change page");
          }}
          pageRangeDisplayed={10}
          pageCount={Math.ceil(logsCount / 10)}
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
