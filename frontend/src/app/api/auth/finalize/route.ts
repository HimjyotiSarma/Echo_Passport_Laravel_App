// app/api/auth/finalize/route.ts

import { cookies } from "next/headers";
import { NextRequest, NextResponse } from "next/server";

export async function GET(request: NextRequest) {
  const cookieStore = await cookies();

  const returnTo = cookieStore.get("return_to")?.value ?? "/";

  // Clean up the temporary cookie
  cookieStore.delete("return_to");

  const url = new URL("/auth/result", request.url);

  url.searchParams.set("returnTo", returnTo);

  return NextResponse.redirect(url, 307);
}