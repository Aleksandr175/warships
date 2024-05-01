import { ICityShort, IFleetWarshipsData, IResource } from "../../types/types";

export interface IMessage {
  id: number;
  content: string;
  templateId: number;
  isRead: 0;
  eventType: string;
  archipelagoId: number;
  coordX: number;
  coordY: number;
  battleLogId: number;
  createdAt: string;
  cityId?: number;
  targetCityId?: number;
  fleetDetails: IFleetWarshipsData[];
  resources: IResource[];
}

export interface IMessagesData {
  messages: IMessage[];
  messagesNumber: number;
  messagesUnread: number;
  cities: ICityShort[];
}
