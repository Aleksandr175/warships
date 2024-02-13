import React from "react";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { SContent, SH1 } from "../styles";

export const Refining = () => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  // TODO: get recipes

  console.log("Refining");

  if (!dictionaries) {
    return null;
  }

  return (
    <SContent>
      <SH1>Refining</SH1>
      <div>
        <p>Refining</p>
      </div>
    </SContent>
  );
};
