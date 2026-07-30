import { requireUser } from "@/src/lib/helper/auth";
import { UserResponse } from "@/Types/Responses";
import { headers } from "next/headers";
import React from "react";
import { UserStoreProvider } from "@/src/providers/user-store-provider";
import Navbar from "../ui/Header/Navbar";

export default async function ProtectedLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const headerList = await headers();
  const returnTo = headerList.get("x-return-to") ?? "/error";
  const userResponse: UserResponse = await requireUser(returnTo);

  return (
    <UserStoreProvider
      initialState={{
        user: userResponse.data,
      }}
    >
      <Navbar />
      {children}
    </UserStoreProvider>
  );
}
