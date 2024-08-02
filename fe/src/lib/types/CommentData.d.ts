import type UserData from './UserData';

export interface CommentData {
	id: string;
	product_id: string;
	user: UserData?;
	user_id: string;
	content: string;
	created_at: string;
	updated_at: string;
}
