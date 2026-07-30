import { NextRequest, NextResponse } from "next/server";
import { RedirectProxy } from "./lib/proxy/RedirectProxy";
import ProtectedRouteProxy from "./lib/proxy/ProtectedRouteProxy";

export default async function proxy(request: NextRequest) {
  const protectedRoutes = ["/chats", "/profile"];
  const pathname = request.nextUrl.pathname;
  const isProtectedRoute = protectedRoutes.some((route) => {
    return pathname.startsWith(route);
  });
  //   if (pathname.startsWith("/api/auth/redirect")) {
  //     return RedirectProxy(request);
  //   }
  if (isProtectedRoute) {
    return ProtectedRouteProxy(request);
  }

  return NextResponse.next();
}

export const config = {
  matcher: ["/api/auth/redirect/:path*", "/chats/:path*", "/profile/:path*"],
};
