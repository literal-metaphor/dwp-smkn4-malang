import type ProductData from "./ProductData";
import type ShopData from "./ShopData";
import type TransactionData from "./TransactionData";
import type TransactionItemData from "./TransactionItemData";
import type UserData from "./UserData";

export const userData: UserData = {
  id: "1",
  username: "user",
  first_name: "User",
  last_name: "Name",
  email: "user@example",
  avatar: null,
  remember_token: "token",
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}

export const shopData: ShopData = {
  id: "1",
  name: "Shop Name",
  owner: userData,
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}

export const productData: ProductData = {
  id: "1",
  shop: shopData,
  name: "Product Name Product Name",
  description: "Product Description",
  price: 10000.00,
  category: "male_fashion",
  images: [],
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}

export const transactionItemData: TransactionItemData = {
  id: "1",
  product: productData,
  quantity: 1,
  status: "pending",
  delivery_date: null,
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}

export const transactionData: TransactionData = {
  id: "1",
  customer: userData,
  items: [transactionItemData, transactionItemData, transactionItemData],
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}