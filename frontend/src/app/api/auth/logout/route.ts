// app/api/auth/logout/route.ts

import { cookies } from "next/headers";
import { NextResponse } from "next/server";

export async function POST() {
  const cookieStore = await cookies();

  const accessToken = cookieStore.get("access_token")?.value;

  // Optional: tell Laravel to revoke/logout
  if (accessToken) {
    await fetch(`${process.env.INTERNAL_BACKEND_URL}/logout`, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json",
      },
    }).catch(() => {
      // Ignore errors—we'll clear cookies anyway.
    });
  }

  cookieStore.delete("access_token");
  cookieStore.delete("refresh_token");

  return NextResponse.json({
    success: true,
  });
}
