import React from "react";
import { render } from "react-dom";
import App from "./components/App";

// @ts-ignore
window.pusher = require("pusher-js");

render(<App />, document.getElementById("app"));
