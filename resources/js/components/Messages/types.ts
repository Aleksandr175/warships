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
  gold: number;
  population: number;
}
