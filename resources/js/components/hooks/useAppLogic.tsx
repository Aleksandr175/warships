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
  IFleets,
  ICityBuildingsData,
} from "../../types/types";
import { httpClient } from "../../httpClient/httpClient";
import Echo from "laravel-echo";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";
import { REFETCH_INTERVAL_MS } from "../../hooks/useCustomQuery";
import { useFetchUserData } from "../../hooks/useFetchUserData";
import { useFetchFleets } from "../../hooks/useFetchFleets";
import { useQueryClient } from "@tanstack/react-query";
import { useBuildings } from "./useBuildings";
import { useCityResources } from "./useCityResources";

export const useAppLogic = () => {
  const queryClient = useQueryClient();

  const [city, setCity] = useState<ICity>();
  const [cities, setCities] = useState<ICity[]>();
  const [cityResources, setCityResources] = useState<ICityResource[]>();
  const [isLoading, setIsLoading] = useState(true);
  const [researches, setResearches] = useState<IUserResearch[] | undefined>();

  const [queueResearch, setQueueResearch] = useState<ICityResearchQueue>();
  const [unreadMessagesNumber, setUnreadMessagesNumber] = useState<number>(0);

  const { updateCityBuildingData } = useBuildings({
    cityId: city?.id,
  });

  const { updateCityResourcesData } = useCityResources({
    cityId: city?.id,
  });

  const setWebsockets = (userId: number): void => {
    console.log("connect to websockets..., userId: ", userId);
    // @ts-ignore
    window.Echo = new Echo({
      broadcaster: "pusher",
      forceTLS: false,
      //encrypted: false,
      //authEndpoint: "/api/broadcasting/auth",
      key: "ASDF",
      wsHost: "localhost",
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
        (newFleetData: {
          fleets: ICityFleet[];
          fleetsIncoming: IFleetIncoming[];
          fleetDetails: IFleetWarshipsData[];
          cities: IMapCity[];
        }) => {
          queryClient.setQueryData(["/fleets"], (oldFleets: IFleets) => {
            return { ...newFleetData };
          });
        }
      )
      // TODO need?
      .listen("CityDataUpdatedEvent", (event: { cities: ICity[] }) => {
        console.log("new city data", event);
        setCities(event.cities);
      })
      .listen(
        "CityResourcesDataUpdatedEvent",
        (newCityResourcesData: {
          cityId: number;
          cityResources: ICityResource[];
        }) => {
          console.log("new city resource data", newCityResourcesData);
          updateCityResourcesData(newCityResourcesData);
        }
      )
      .listen(
        "CityBuildingDataUpdatedEvent",
        (newCityBuildings: ICityBuildingsData) => {
          updateCityBuildingData(newCityBuildings);
        }
      )
      // just for test http://localhost/test-event
      .listen("TestEvent", (event: { cities: ICity[] }) => {
        console.log("test event1", event);
      });
  };

  const queryDictionaries = useFetchDictionaries({
    refetchInterval: REFETCH_INTERVAL_MS,
  });

  const queryUserData = useFetchUserData({
    refetchInterval: REFETCH_INTERVAL_MS,
  });

  const userId = queryUserData?.data?.data.userId;

  useEffect(() => {
    if (userId) {
      setWebsockets(userId);
    }
  }, [userId]);

  const dictionaries = queryDictionaries.data;

  useEffect(() => {
    httpClient.get("/user").then((response) => {
      httpClient.get("/dictionaries").then((respDictionary) => {
        setCity(response.data.data.cities[0]);
        setCities(response.data.data.cities);

        setUnreadMessagesNumber(respDictionary.data.unreadMessagesNumber);

        setIsLoading(false);
      });
    });
  }, []);

  useEffect(() => {
    const cityInfo = cities?.find((c) => c.id === city?.id);

    setCity(cityInfo);
  }, [cities]);

  useEffect(() => {
    getResearches();
  }, [city]);

  // TODO: refactor it. Temporary solution for getting updates while Websockets isn't working
  useEffect(() => {
    const updateTimer = setInterval(() => {
      getResearches();
    }, 5000);

    return () => {
      clearTimeout(updateTimer);
    };
  }, [city]);

  const updateCityResources = (cityResources: ICityResource[]) => {
    const tempCity = Object.assign({}, city);

    tempCity.resources = cityResources;

    setCity(tempCity);
  };

  const queryFleets = useFetchFleets();

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
    fleets: queryFleets?.data?.fleets || [],
    fleetCitiesDictionary: queryFleets?.data?.cities || [],
    fleetsIncoming: queryFleets?.data?.fleetsIncoming || [],
    dictionaries,
    updateCityResources,
    queueResearch,
    setQueueResearch,
    fleetDetails: queryFleets?.data?.fleetDetails || [],
    userId,
    getResearches,
    logout,
    unreadMessagesNumber,
  };
};
