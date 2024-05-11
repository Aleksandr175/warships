import { useEffect, useState } from "react";
import {
  ICity,
  ICityBuilding,
  ICityBuildingQueue,
  ICityFleet,
  ICityResearchQueue,
  IFleetWarshipsData,
  IFleetIncoming,
  IMapCity,
  IUserResearch,
  ICityResource,
} from "../../types/types";
import { httpClient } from "../../httpClient/httpClient";
import Echo from "laravel-echo";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { REFETCH_INTERVAL_MS } from "../../hooks/useCustomQuery";

export const useAppLogic = () => {
  const [city, setCity] = useState<ICity>();
  const [cities, setCities] = useState<ICity[]>();
  const [cityResources, setCityResources] = useState<ICityResource[]>();
  const [isLoading, setIsLoading] = useState(true);
  const [buildings, setBuildings] = useState<ICityBuilding[] | undefined>();
  const [researches, setResearches] = useState<IUserResearch[] | undefined>();
  const [fleets, setFleets] = useState<ICityFleet[]>();
  const [fleetsIncoming, setFleetsIncoming] = useState<IFleetIncoming[]>();
  const [fleetDetails, setFleetDetails] = useState<IFleetWarshipsData[]>();
  const [fleetCitiesDictionary, setFleetCitiesDictionary] =
    useState<IMapCity[]>();

  const [queue, setQueue] = useState<ICityBuildingQueue>();
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
          cities: IMapCity[];
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

  const queryDictionaries = useFetchDictionaries({
    refetchInterval: REFETCH_INTERVAL_MS,
  });

  const dictionaries = queryDictionaries.data;

  useEffect(() => {
    httpClient.get("/user").then((response) => {
      httpClient.get("/dictionaries").then((respDictionary) => {
        setCity(response.data.data.cities[0]);
        setCities(response.data.data.cities);
        setUserId(response.data.data.userId);

        setUnreadMessagesNumber(respDictionary.data.unreadMessagesNumber);

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
    getFleets();
  }, [city]);

  // TODO: refactor it. Temporary solution for getting updates while Websockets isn't working
  useEffect(() => {
    const updateTimer = setInterval(() => {
      getCityResources();
      getBuildings();
      getResearches();
      getFleets();
    }, 5000);

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
    fleetDetails,
    userId,
    getResearches,
    logout,
    unreadMessagesNumber,
  };
};
