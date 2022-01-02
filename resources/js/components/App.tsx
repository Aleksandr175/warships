import React, { useState } from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import { Overview } from "./Overview";
import { Buildings } from "./Buildings";
import { Researches } from "./Researches";

const App = () => {
    return (
        <Router>
            <div className="container">
                <div className={"row"}>
                    <div className={"col-3"}>
                        <Link to={"/dashboard"}>Обзор</Link>
                        <br />
                        <Link to={"/buildings"}>Постройки</Link>
                        <br />
                        <Link to={"/researches"}>Исследования</Link>
                    </div>
                    <div className={"col-9"}>
                        <Routes>
                            <Route path={"dashboard"} element={<Overview />} />
                            <Route path={"buildings"} element={<Buildings />} />
                            <Route
                                path={"researches"}
                                element={<Researches />}
                            />
                        </Routes>
                    </div>
                </div>
            </div>
        </Router>
    );
};

export default App;
