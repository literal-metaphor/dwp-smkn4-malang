import { writable, type Writable } from 'svelte/store';

export const authStatus: Writable<boolean> = writable(false);
