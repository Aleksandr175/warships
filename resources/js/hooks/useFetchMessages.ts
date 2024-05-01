import { useCustomQuery } from "./useCustomQuery";
import { IMessagesData } from "../components/Messages/types";

export const useFetchMessages = (page: number) => {
  return useCustomQuery<IMessagesData>({
    url: `/messages?page=` + page,
    queryParams: {},
  });
};
