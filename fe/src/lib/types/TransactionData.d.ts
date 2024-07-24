import type TransactionItemData from "./TransactionItemData";

export default interface TransactionData {
  id: string;
  customer_id: string;
  items: TransactionItemData[];
  created_at: string;
  updated_at: string;
}