"use client";

import { useRouter } from "next/navigation";
import { useUserStore } from "@/src/providers/user-store-provider";

export function LogoutButton() {
  const router = useRouter();
  const clearUser = useUserStore((state) => state.clearUser);

  const logout = async () => {
    const response = await fetch("/api/auth/logout", {
      method: "POST",
    });

    if (!response.ok) {
      throw new Error("Logout failed");
    }

    clearUser();

    router.replace("/login");
  };

  return <button onClick={logout}>Logout</button>;
}
