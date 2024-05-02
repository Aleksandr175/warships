import { ICityShort, IFleetWarshipsData, IResource } from "../../types/types";
import { IBattleLog, IBattleLogDetail } from "./MessageBattleLog";

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
  battleLog: IBattleLog;
  battleLogDetails: IBattleLogDetail[];
}

export interface IMessagesData {
  messages: IMessage[];
  messagesNumber: number;
  messagesUnread: number;
  cities: ICityShort[];
}

export interface IMessageData {
  message: IMessage;
  cities: ICityShort[];
}
