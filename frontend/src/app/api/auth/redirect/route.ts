import { cookies } from "next/headers";
import { NextRequest, NextResponse } from "next/server";

export async function GET(request: NextRequest) {
  console.log("REQUEST url", request.url);
  console.log(request.nextUrl.href);
  console.log(request.nextUrl.search);
  const returnTo = request.nextUrl.searchParams.get("returnTo") ?? "/";
  // const clientId = request.nextUrl.searchParams.get("client_id");
  // const scope = request.nextUrl.searchParams.get("scope");
  // console.log("CURRENT CLIENT ID: ", clientId);

  const safeReturnTo = returnTo.startsWith("/") ? returnTo : "/";

  const cookieStore = await cookies();

  cookieStore.set("return_to", safeReturnTo, {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 600
  })

  const backendUrl = new URL(
    "/auth/redirect",
    process.env.NEXT_PUBLIC_BACKEND_URL,
  );

  backendUrl.searchParams.set("client_id", process.env.CLIENT_ID!);

  if (process.env.SITE_SCOPES) {
    backendUrl.searchParams.set("scope", process.env.SITE_SCOPES);
  }

  // if (clientId) {
  //   backendUrl.searchParams.set("client_id", clientId);
  // }
  // if (scope) {
  //   backendUrl.searchParams.set("scope", scope);
  // }
  return NextResponse.redirect(backendUrl, 307);
}
