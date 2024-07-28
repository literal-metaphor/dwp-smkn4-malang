import type TransactionItemData from "./TransactionItemData";
import type UserData from "./UserData";

export default interface TransactionData {
  id: string;
  customer: UserData?;
  customer_id: string;
  items: TransactionItemData[];
  method: "cod";
  created_at: string;
  updated_at: string;
}