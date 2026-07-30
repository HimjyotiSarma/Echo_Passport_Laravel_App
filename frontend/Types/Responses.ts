export type UserData = {
  id: string;
  name: string;
  email: string;
  email_verified?: boolean;
  created_at?: string;
  updated_at?: string;
  // role: string;
};
// Updated it as per Backend Responses
export interface UserResponse {
  data: UserData;
  success: boolean;
  status: number;
  message: string;
}
