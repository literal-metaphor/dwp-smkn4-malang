import type { FileData } from "./FileData";

export default interface UserData {
	id: string;
	username: string;
	first_name: string;
	last_name: string?;
	email: string;
	avatar_id: string?;
	avatar: FileData?;
	remember_token: string;
	is_shop: boolean;
	created_at: string;
	updated_at: string;
}
