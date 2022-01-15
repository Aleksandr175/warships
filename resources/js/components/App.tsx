import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import { Overview } from "./Overview";
import { Buildings } from "./Buildings";
import { Researches } from "./Researches";
import { httpClient } from "../httpClient/httpClient";

interface ICity {
    title: string;
    coordX: number;
    coordY: number;
    gold: number;
    population: number;
}

const App = () => {
    const [userInfo, setUserInfo] = useState();
    const [city, setCity] = useState<ICity>();

    useEffect(() => {
        httpClient.get("/user").then((response) => {
            console.log(response);
            setUserInfo(response.data.data);
            setCity(response.data.data.cities[0]);
        });
    }, []);

    return (
        <Router>
            <div className="container">
                {city && (
                    <>
                        Выбранный остров: {city.title}
                        <br />
                        Координаты: {city.coordX}:{city.coordY}
                        <br />
                        Золото: {city.gold}
                        <br />
                        Население: {city.population}
                    </>
                )}
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
