import { writable, type Writable } from "svelte/store";
import { api } from "./api";

export const authStatus: Writable<boolean> = writable(false);

export async function guard(id: string, remember_token: string): Promise<void> {
  try {
    const res = await api.get(`/auth/${id}`, { headers: { Authorization: `Bearer ${remember_token}` } });
    if (!res.status.toString().startsWith("2")) {
      throw new Error(res.data.message);
    }
  } catch (err) {
    if (err instanceof Error) {
      throw err;
    } else {
      throw new Error("Terjadi kesalahan autentikasi");
    }
  }
}