import React from "react";
import { createRoot } from "react-dom/client";
import App from "./components/App";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";

const queryClient = new QueryClient();

// @ts-ignore
window.pusher = require("pusher-js");

const root = createRoot(document.getElementById("app") as HTMLElement);
root.render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <App />
    </QueryClientProvider>
    ,
  </React.StrictMode>
);
