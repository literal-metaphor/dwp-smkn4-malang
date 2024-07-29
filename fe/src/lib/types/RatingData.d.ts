export interface RatingData {
  id: string;
  product_id: string;
  user_id: string;
  rating: 1 | 2 | 3 | 4 | 5;
  created_at: string;
  updated_at: string;
}