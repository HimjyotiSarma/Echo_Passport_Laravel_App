import { NextRequest, NextResponse } from "next/server";

export function RedirectProxy(request: NextRequest) {
  console.log("User Proxy called successfully");

  const clientId = process.env.CLIENT_ID;
  const scope = process.env.SITE_SCOPES;

  if (!clientId) {
    return NextResponse.json(
      {
        error: "Client Id Unavailable",
        message: "Client Id Unavailable. Please Contact Site Administrator",
      },
      {
        status: 500,
      },
    );
  }

  const url = request.nextUrl.clone();
  url.searchParams.set("client_id", clientId);
  if (scope) {
    url.searchParams.set("scope", scope);
  }
  console.log("REWRITTEN URL IS : ", url);
  return NextResponse.rewrite(url);
}
