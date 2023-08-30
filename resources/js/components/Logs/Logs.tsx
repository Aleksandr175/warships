import React, { useEffect, useState } from "react";
import { IWarship } from "../../types/types";
import { httpClient } from "../../httpClient/httpClient";
import ReactPaginate from "react-paginate";
import { NavLink } from "react-router-dom";
import { SContent } from "../styles";

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
      setLogs(resp.data.battleLogs);
      setLogsDetails(resp.data.battleLogsDetails);
      setLogsCount(resp.data.battleLogsCount);
      setIsLoading(false);
    });
  };

  return (
    <SContent>
      {!logs?.length && <>No logs yet</>}

      {logs.map((log) => {
        return (
          <>
            <div className={"row"}>
              <div className={"col-4"}>Date</div>
              <div className={"col-4"}>Type</div>
              <div className={"col-4"}>Result</div>
            </div>

            <div className={"row"}>
              <div className={"col-4"}>{log.date}</div>
              <div className={"col-4"}>
                {log.attackerUserId === userId ? "Attack" : "Defend"}
              </div>
              <div className={"col-4"}>
                {log.winner === "attacker" ? "Victory" : "Defeat"}
              </div>
            </div>

            <NavLink to={"/logs/" + log.battleLogId}>Show</NavLink>

            <hr />
          </>
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
