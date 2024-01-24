import { useEffect, useState } from "react";
import {
  ICity,
  ICityBuilding,
  ICityBuildingQueue,
  ICityFleet,
  ICityResearchQueue,
  ICityResources,
  ICityWarship,
  ICityWarshipQueue,
  IDictionary,
  IFleetWarshipsData,
  IFleetIncoming,
  IMapCity,
  IUserResearch,
  ICityResource,
  IResourceDictionary,
} from "../../types/types";
import { httpClient } from "../../httpClient/httpClient";
import Echo from "laravel-echo";

export const useAppLogic = () => {
  const [city, setCity] = useState<ICity>();
  const [cities, setCities] = useState<ICity[]>();
  const [cityResources, setCityResources] = useState<ICityResource[]>();
  const [isLoading, setIsLoading] = useState(true);
  const [dictionaries, setDictionaries] = useState<IDictionary>();
  const [buildings, setBuildings] = useState<ICityBuilding[] | undefined>();
  const [researches, setResearches] = useState<IUserResearch[] | undefined>();
  const [warships, setWarships] = useState<ICityWarship[] | undefined>();
  const [fleets, setFleets] = useState<ICityFleet[]>();
  const [fleetsIncoming, setFleetsIncoming] = useState<IFleetIncoming[]>();
  const [fleetDetails, setFleetDetails] = useState<IFleetWarshipsData[]>();
  const [fleetCitiesDictionary, setFleetCitiesDictionary] =
    useState<IMapCity[]>();
  const [resourcesDictionary, setResourcesDictionary] =
    useState<IResourceDictionary[]>();

  const [queue, setQueue] = useState<ICityBuildingQueue>();
  const [queueWarship, setQueueWarship] = useState<ICityWarshipQueue[]>();
  const [queueResearch, setQueueResearch] = useState<ICityResearchQueue>();
  const [userId, setUserId] = useState<number>();
  const [unreadMessagesNumber, setUnreadMessagesNumber] = useState<number>(0);

  const setWebsockets = (userId: number): void => {
    // @ts-ignore
    window.Echo = new Echo({
      broadcaster: "pusher",
      forceTLS: false,
      //encrypted: false,
      //authEndpoint: "/api/broadcasting/auth",
      key: "ASDF",
      wsHost: "127.0.0.1",
      wsPort: 6001,
      //wssport: 8000,
      transports: ["websocket"],
      //enabledTransports: ["ws", "wss"],
      enabledTransports: ["ws"],
      //disableStats: true,
    });

    // @ts-ignore
    window.Echo.private("user." + userId)
      .listen(
        "FleetUpdatedEvent",
        (event: {
          fleets: ICityFleet[];
          fleetsIncoming: IFleetIncoming[];
          fleetsDetails: IFleetWarshipsData[];
          cities: ICity[];
        }) => {
          console.log("new fleet data", event);
          setFleets(event.fleets);
          setFleetsIncoming(event.fleetsIncoming);
          setFleetDetails(event.fleetsDetails);
          setFleetCitiesDictionary(event.cities);
        }
      )
      .listen("CityDataUpdatedEvent", (event: { cities: ICity[] }) => {
        console.log("new city data", event);
        setCities(event.cities);
      })
      .listen("TestEvent", (event: { cities: ICity[] }) => {
        console.log("test event", event);
      });
  };

  useEffect(() => {
    httpClient.get("/user").then((response) => {
      httpClient.get("/dictionaries").then((respDictionary) => {
        setCity(response.data.data.cities[0]);
        setCities(response.data.data.cities);
        setDictionaries(respDictionary.data);
        setUserId(response.data.data.userId);
        setUnreadMessagesNumber(respDictionary.data.unreadMessagesNumber);
        setResourcesDictionary(respDictionary.data.resourcesDictionary);

        setWebsockets(response.data.data.userId);
        setIsLoading(false);
      });
    });
  }, []);

  useEffect(() => {
    const cityInfo = cities?.find((c) => c.id === city?.id);

    setCity(cityInfo);
  }, [cities]);

  useEffect(() => {
    getCityResources();
    getBuildings();
    getResearches();
    getWarships();
    getFleets();
  }, [city]);

  // TODO: refactor it. Temporary solution for getting updates while Websockets isn't working
  useEffect(() => {
    const updateTimer = setInterval(() => {
      getCityResources();
      getBuildings();
      getResearches();
      getWarships();
      getFleets();
    }, 3000);

    return () => {
      clearTimeout(updateTimer);
    };
  }, [city]);

  const getCityResources = () => {
    if (!city) return;

    httpClient.get("/city/" + city.id).then((response) => {
      setCityResources(response.data.data);
    });
  };

  const updateCityResources = (cityResources: ICityResource[]) => {
    const tempCity = Object.assign({}, city);

    tempCity.resources = cityResources;

    setCity(tempCity);
  };

  const getBuildings = () => {
    if (!city?.id) {
      return;
    }

    httpClient.get("/buildings?cityId=" + city?.id).then((response) => {
      setBuildings(response.data.buildings);
      setQueue(response.data.buildingQueue);
    });
  };

  const getWarships = () => {
    if (!city?.id) {
      return;
    }

    httpClient.get("/warships?cityId=" + city?.id).then((response) => {
      setWarships(response.data.warships);
      setQueueWarship(response.data.queue);
    });

    getCityResources();
  };

  const getFleets = () => {
    httpClient.get("/fleets").then((response) => {
      setFleets(response.data.fleets);
      setFleetsIncoming(response.data.fleetsIncoming);
      setFleetDetails(response.data.fleetDetails);
      setFleetCitiesDictionary(response.data.cities);
    });
  };

  const getResearches = () => {
    httpClient.get("/researches").then((response) => {
      setResearches(response.data.researches);
      setQueueResearch(response.data.queue);
    });
  };

  const getProductionGold = () => {
    if (buildings) {
      // TODO change 2
      const miner = buildings.find((building) => {
        return building.buildingId === 2 && building.cityId === city?.id;
      });

      if (miner) {
        const lvl = miner.lvl;

        const production = dictionaries?.buildingsProduction?.find(
          (bp) =>
            bp.buildingId === miner.buildingId &&
            bp.lvl === lvl &&
            bp.resource === "gold"
        );

        return production?.qty;
      }
    }

    return 0;
  };

  const selectCity = (c: ICity) => {
    setCity(c);
  };

  // @ts-ignore
  const logout = (e) => {
    e.preventDefault();

    httpClient.get("/logout").then(() => {
      window.location.pathname = "/";
    });
  };

  return {
    isLoading,
    city,
    cities,
    selectCity,
    cityResources,
    getProductionGold,
    fleets,
    fleetCitiesDictionary,
    fleetsIncoming,
    dictionaries,
    updateCityResources,
    buildings,
    setBuildings,
    getBuildings,
    queue,
    setQueue,
    queueResearch,
    setQueueResearch,
    warships,
    setWarships,
    getWarships,
    queueWarship,
    setQueueWarship,
    fleetDetails,
    userId,
    getResearches,
    logout,
    unreadMessagesNumber,
    resourcesDictionary,
  };
};
