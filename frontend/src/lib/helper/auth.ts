// lib/auth.ts
import { UserResponse } from "@/Types/Responses";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { cache } from "react";

export const getCurrentUser = cache(async (): Promise<UserResponse | null> => {
  const cookieStore = await cookies();

  const accessToken = cookieStore.get("access_token")?.value;

  console.log("CURRENT ACCESS TOKEN FOUND IS :", accessToken);

  if (!accessToken) {
    return null;
  }

  const response = await fetch(`${process.env.INTERNAL_BACKEND_URL}/api/user`, {
    headers: {
      Cookie: cookieStore.toString(),
    },
    cache: "no-store",
  });

  if (response.status === 401) {
    return null;
  }

  if (!response.ok) {
    const body = await response.json();
    console.log("ERROR FOUND IS :", body ?? "Server Error");
    throw new Error(body.message ?? "Failed to fetch user");
  }

  return response.json();
});

export async function requireUser(returnTo?: string) {
  const user = await getCurrentUser();

  if (!user) {
    const redirectUrl = new URL(
      "/api/auth/redirect",
      process.env.NEXT_PUBLIC_BASE_URL,
    );
    if (returnTo) {
      redirectUrl.searchParams.set("returnTo", returnTo);
    }

    redirect(redirectUrl.toString());
  }

  return user;
}
