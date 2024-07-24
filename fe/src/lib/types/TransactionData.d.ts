import type TransactionItemData from "./TransactionItemData";
import type UserData from "./UserData";

export default interface TransactionData {
  id: string;
  customer: UserData;
  items: TransactionItemData[];
  created_at: string;
  updated_at: string;
}