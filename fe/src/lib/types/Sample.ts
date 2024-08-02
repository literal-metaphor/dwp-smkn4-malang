import type { CommentData } from './CommentData';
import type ProductData from './ProductData';
import type { RatingData } from './RatingData';
import type TransactionData from './TransactionData';
import type TransactionItemData from './TransactionItemData';
import type TrolleyItem from './TrolleyItem';
import type UserData from './UserData';

export const userData: UserData = {
	id: '1',
	username: 'user',
	first_name: 'User',
	last_name: 'Name',
	email: 'user@example',
	avatar: null,
	avatar_id: null,
	remember_token: 'token',
	is_shop: false,
	created_at: '2020-01-01',
	updated_at: '2020-01-01'
};

export const productData: ProductData = {
	id: '1',
	owner: userData,
	owner_id: userData.id,
	name: 'Product Name Product Name',
	description: 'Product Description',
	price: 10000.0,
	category: 'male_fashion',
	images: [],
	created_at: '2020-01-01',
	updated_at: '2020-01-01'
};

export const transactionItemData: TransactionItemData = {
	id: '1',
	product_id: productData.id,
	product: productData,
	quantity: 1,
	status: 'pending',
	delivery_date: null,
	created_at: '2020-01-01',
	updated_at: '2020-01-01'
};

export const transactionData: TransactionData = {
	id: '1',
	customer_id: userData.id,
	customer: userData,
	items: [transactionItemData, transactionItemData, transactionItemData],
	method: 'cod',
	latitude: 0,
	longitude: 0,
	address_criteria: 'address criteria',
	created_at: '2020-01-01',
	updated_at: '2020-01-01'
};

export const ratingData: RatingData = {
	id: '1',
	product_id: productData.id,
	user_id: userData.id,
	rating: (Math.floor(Math.random() * 5) + 1) as 1 | 2 | 3 | 4 | 5,
	created_at: '2020-01-01',
	updated_at: '2020-01-01'
}

export const commentData: CommentData = {
	id: '1',
	product_id: productData.id,
	user: userData,
	user_id: userData.id,
	content: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat laboriosam repudiandae dolorem ullam sit fuga quasi error ab. Architecto nihil illum sequi asperiores praesentium porro nisi qui voluptas exercitationem tempora.',
	created_at: '2020-01-01',
	updated_at: '2020-01-01'
}

export const trolleyItemData: TrolleyItem = {
	owner: userData,
	product: productData,
	quantity: 1
}