export interface IMessage {
  id: number;
  content: string;
  templateId: number;
  isRead: 0;
  eventType: number;
  archipelagoId: number;
  coordX: number;
  coordY: number;
  battleLogId: number;
  createdAt: string;
}
