"use client";

import Link from "next/link";
import { useUserStore } from "@/src/providers/user-store-provider";
import { LogoutButton } from "../Buttons/Logout";

export default function Navbar() {
  const id = useUserStore((state) => state.user?.id);
  const name = useUserStore((state) => state.user?.name);
  const clearUser = useUserStore((state) => state.clearUser);

  return (
    <nav className="flex items-center justify-between border-b bg-white px-6 py-4">
      <Link href="/" className="text-xl font-semibold">
        Echo
      </Link>

      <div className="flex items-center gap-4">
        {id ? (
          <>
            <span>Welcome, {name}</span>

            <Link
              href="/chat"
              className="rounded bg-blue-600 px-4 py-2 text-white"
            >
              Chat
            </Link>

            <LogoutButton />
          </>
        ) : (
          <a
            href={`${process.env.NEXT_PUBLIC_BACKEND_URL}/login`}
            className="rounded bg-blue-600 px-4 py-2 text-white"
          >
            Login
          </a>
        )}
      </div>
    </nav>
  );
}
