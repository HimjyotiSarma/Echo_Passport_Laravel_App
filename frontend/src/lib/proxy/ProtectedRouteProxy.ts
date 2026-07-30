import { NextRequest, NextResponse } from "next/server";

export default function ProtectedRouteProxy(request: NextRequest) {
  const response = NextResponse.next();
  response.headers.set(
    "x-return-to",
    request.nextUrl.pathname + request.nextUrl.search,
  );
  return response;
}
