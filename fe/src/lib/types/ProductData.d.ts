import type ShopData from "./ShopData";

export default interface ProductData {
  id: string;
  shop: ShopData;
  name: string;
  description: string;
  price: number;
  category: string;
  images: string[]?;
  created_at: string;
  updated_at: string;
}