import type UserData from "./UserData";

export default interface ShopData {
  id: string;
  name: string;
  owner: UserData;
  created_at: string;
  updated_at: string;
}