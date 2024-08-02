import type UserData from '$lib/types/UserData';
import type ProductData from '$lib/types/ProductData';

export default interface TrolleyItem {
	owner: UserData;
	product: ProductData;
	quantity: number;
}
