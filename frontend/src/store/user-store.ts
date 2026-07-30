import { createStore } from "zustand/vanilla";
import { UserData } from "@/Types/Responses";
import { devtools } from "zustand/middleware";

// export type UserState = {
//   id: UserData["id"] | null;
//   name: UserData["name"] | null;
//   email: UserData["email"] | null;
//   email_verified: UserData["email_verified"] | null;
//   created_at: UserData["created_at"] | null;
//   updated_at: UserData["updated_at"] | null;
//   // role: UserData["role"] | null;
// };
export type UserState = {
  user: UserData | null;
};
export type UserActions = {
  // fetchUser: () => Promise<void>;
  setUser: (user: UserData) => void;
  clearUser: () => void;
};
// This will change with the UserData State
// export const defaultInitialState: UserState = {
//   id: null,
//   name: null,
//   email: null,
//   email_verified: null,
//   created_at: null,
//   updated_at: null,
//   // role: null,
// };
export const defaultInitialState: UserState = {
  user: null,
};
export type UserStore = UserState & UserActions;

export const createUserStore = (
  initialState: UserState = defaultInitialState,
) => {
  return createStore<UserStore>()(
    devtools((set) => ({
      ...initialState,
      fetchUser: async () => {
        const response = await fetch("/api/user", {
          method: "GET",
        });
        if (!response.ok) {
          throw new Error("Failed to fetch User");
        }
        const result: UserData = await response.json();
        set({
          // id: result.id,
          // name: result.name,
          // email: result.email,
          // email_verified: result.email_verified,
          // created_at: result.created_at,
          // updated_at: result.updated_at,
          // role: result.role,
          user: result,
        });
      },
      setUser: (user: UserData) =>
        set({
          // id: user.id,
          // name: user.name,
          // email: user.email,
          // email_verified: user.email_verified,
          // created_at: user.created_at,
          // updated_at: user.updated_at,
          // role: user.role,
          user,
        }),
      clearUser: () =>
        set({
          // id: initialState.id,
          // name: initialState.name,
          // email: initialState.email,
          // email_verified: initialState.email_verified,
          // created_at: initialState.created_at,
          // updated_at: initialState.updated_at,
          // role: initialState.role,
          user: null,
        }),
    })),
  );
};
