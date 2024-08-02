import type UserData from './UserData';

export default interface ProductData {
	id: string;
	owner_id: string;
	owner: UserData?;
	name: string;
	description: string;
	price: number;
	category: 'food' | 'drink' | 'female_fashion' | 'male_fashion' | 'child_fashion' | 'furniture';
	images: string[]?;
	created_at: string;
	updated_at: string;
}
