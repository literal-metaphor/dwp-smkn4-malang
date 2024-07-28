import type ProductData from "./ProductData";
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

export const productData: ProductData = {
  id: "1",
  owner: userData,
  owner_id: userData.id,
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
  product_id: productData.id,
  product: productData,
  quantity: 1,
  status: "pending",
  delivery_date: null,
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}

export const transactionData: TransactionData = {
  id: "1",
  customer_id: userData.id,
  customer: userData,
  items: [transactionItemData, transactionItemData, transactionItemData],
  method: "cod",
  created_at: "2020-01-01",
  updated_at: "2020-01-01",
}