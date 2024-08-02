import type ProductData from './ProductData';

export default interface TransactionItemData {
	id: string;
	product: ProductData?;
	product_id: string;
	quantity: number;
	status: 'pending' | 'delivered';
	delivery_date: string | null;
	created_at: string;
	updated_at: string;
}
