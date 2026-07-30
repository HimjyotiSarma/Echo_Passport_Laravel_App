"use client";

import { createContext, ReactNode, useContext, useState } from "react";
import {
  UserState,
  type UserStore,
  createUserStore,
} from "../store/user-store";
import { useStore } from "zustand";
import { UserData } from "@/Types/Responses";

export type UserStoreApi = ReturnType<typeof createUserStore>;

export const UserStoreContext = createContext<UserStoreApi | undefined>(
  undefined,
);

export interface UserStoreProviderProps {
  children: ReactNode;
  initialState?: UserState;
}

export const UserStoreProvider = ({
  children,
  initialState,
}: UserStoreProviderProps) => {
  const [store] = useState(() => createUserStore(initialState));
  return (
    <UserStoreContext.Provider value={store}>
      {children}
    </UserStoreContext.Provider>
  );
};

export const useUserStore = <T,>(selector: (store: UserStore) => T): T => {
  const userStoreContext = useContext(UserStoreContext);
  if (!userStoreContext) {
    throw new Error("useUserStore must be used within UserStoreProvider");
  }
  return useStore(userStoreContext, selector);
};
