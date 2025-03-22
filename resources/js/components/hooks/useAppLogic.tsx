import { useEffect, useState } from "react";
import {
  ICity,
  ICityResource,
  IFleetsData,
  ICityBuildingsData,
  ICityWarshipsData,
  IResearchesData,
  ICityWarshipsDataChanges,
  IResourcesDataChanges,
  IFleetDataChanges,
  IRefiningDataChanges,
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
import { useCityWarships } from "./useCityWarships";
import { useResearches } from "./useResearches";
import { IMessagesData } from "../Messages/types";
import { useCityRefining } from "./useCityRefining";
import { useFleets } from "./useFleets";
import { filterCityResources, filterUserResources } from "../../utils";
import { useUserResources } from "./useUserResources";

export const useAppLogic = () => {
  const queryClient = useQueryClient();

  const [city, setCity] = useState<ICity>();
  const [cities, setCities] = useState<ICity[]>();

  const [unreadMessagesNumber, setUnreadMessagesNumber] = useState<number>(0);

  const { updateCityBuildingData } = useBuildings({
    cityId: city?.id,
  });

  const { updateCityResourcesData, applyCityResourcesChangesData } =
    useCityResources({
      cityId: city?.id,
    });

  const { updateCityWarshipsData, applyCityWarshipChangesData } =
    useCityWarships({
      cityId: city?.id,
    });

  const { updateResearchesData } = useResearches({
    cityId: city?.id,
  });

  const { updateCityRefiningData } = useCityRefining({
    cityId: city?.id,
  });

  const { applyFleetsChangesData } = useFleets();

  const { applyUserResourcesChanges } = useUserResources();

  const setWebsockets = (userId: number): void => {
    console.log("connect to websockets..., userId: ", userId);
    // @ts-ignore
    window.Echo = new Echo({
      broadcaster: "pusher",
      forceTLS: false,
      key: "ASDF",
      wsHost: "localhost",
      wsPort: 6001,
      transports: ["websocket"],
      enabledTransports: ["ws"],
      authEndpoint: "/broadcasting/auth",
      auth: {
        headers: {
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content"),
          Accept: "application/json",
        },
      },
    });

    // @ts-ignore
    window.Echo.private(`user.${userId}`)
      .listen("FleetUpdatedEvent", (newFleetData: IFleetsData) => {
        console.log("new fleets data", newFleetData);
        queryClient.setQueryData(["/fleets"], (oldFleets: IFleetsData) => {
          return { ...newFleetData };
        });
      })
      .listen(
        "FleetDataChangesEvent",
        (fleetDataChanges: IFleetDataChanges) => {
          console.log("Fleet data CHANGES", fleetDataChanges);
          applyFleetsChangesData(fleetDataChanges);
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
        "CityResourcesDataChangesEvent",
        (newCityResourcesDataChanges: IResourcesDataChanges) => {
          console.log(
            "!!!new city resource data changes",
            newCityResourcesDataChanges
          );

          applyCityResourcesChangesData({
            cityResourcesChanges: filterCityResources(
              newCityResourcesDataChanges.resourceChanges,
              // @ts-ignore
              dictionaries?.resourcesDictionary
            ),
            cityId: newCityResourcesDataChanges.cityId,
          });

          applyUserResourcesChanges({
            resourceChanges: filterUserResources(
              newCityResourcesDataChanges.resourceChanges,
              // @ts-ignore
              dictionaries?.resourcesDictionary
            ),
          });
        }
      )
      .listen(
        "CityWarshipsDataUpdatedEvent",
        (newCityWarshipsData: ICityWarshipsData) => {
          console.log("new city warships data", newCityWarshipsData);
          updateCityWarshipsData(newCityWarshipsData);
        }
      )
      .listen(
        "CityWarshipsDataChangesEvent",
        (dataChanges: ICityWarshipsDataChanges) => {
          console.log("new city warships changes data", dataChanges);
          applyCityWarshipChangesData(dataChanges);
        }
      )
      .listen(
        "CityBuildingDataUpdatedEvent",
        (newCityBuildings: ICityBuildingsData) => {
          updateCityBuildingData(newCityBuildings);
        }
      )
      .listen(
        "ResearchesDataUpdatedEvent",
        (newResearchesData: IResearchesData) => {
          console.log("new researches data", newResearchesData);
          updateResearchesData(newResearchesData);
        }
      )
      .listen("MessagesDataUpdatedEvent", (newMessagesData: IMessagesData) => {
        console.log("new messages data", newMessagesData);
        setUnreadMessagesNumber(newMessagesData.messagesUnread);
      })
      .listen(
        "CityRefiningDataChangesEvent",
        (newData: IRefiningDataChanges) => {
          console.log("!new refining data CHANGES", newData);
          updateCityRefiningData({
            cityId: newData.cityId,
            refiningQueue: newData.refiningQueue,
            refiningSlots: newData.maxRefiningSlots,
          });

          applyCityResourcesChangesData({
            cityResourcesChanges: newData.resourceChanges,
            cityId: newData.cityId,
          });
        }
      )
      // just for test http://localhost/test-event
      .listen(".TestEvent", (event: { cities: ICity[] }) => {
        console.log("test event received", event);
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
    if (dictionaries) {
      setUnreadMessagesNumber(dictionaries.unreadMessagesNumber);
    }
  }, [dictionaries]);

  useEffect(() => {
    if (queryUserData?.data) {
      setCity(queryUserData.data.data.cities[0]);
      setCities(queryUserData.data.data.cities);
    }
  }, [queryUserData?.data]);

  useEffect(() => {
    const cityInfo = cities?.find((c) => c.id === city?.id);

    setCity(cityInfo);
  }, [cities]);

  const updateCityResources = (cityResources: ICityResource[]) => {
    const tempCity = Object.assign({}, city);

    tempCity.resources = cityResources;

    setCity(tempCity);
  };

  const queryFleets = useFetchFleets();

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
    city,
    cities,
    selectCity,
    fleets: queryFleets?.data?.fleets || [],
    fleetCitiesDictionary: queryFleets?.data?.cities || [],
    dictionaries,
    updateCityResources,
    fleetDetails: queryFleets?.data?.fleetDetails || [],
    userId,
    logout,
    unreadMessagesNumber,
  };
};
