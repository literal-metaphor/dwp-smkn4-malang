export default interface UserData {
  id: string;
  username: string;
  first_name: string;
  last_name: string?;
  email: string;
  avatar: string?;
  remember_token: string;
  is_shop: boolean;
  created_at: string;
  updated_at: string;
}