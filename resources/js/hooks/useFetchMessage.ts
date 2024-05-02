import { useCustomQuery } from "./useCustomQuery";
import { IMessageData } from "../components/Messages/types";

export const useFetchMessage = (messageId: number) => {
  return useCustomQuery<IMessageData>({
    url: `/messages/` + messageId,
    queryParams: {},
  });
};
