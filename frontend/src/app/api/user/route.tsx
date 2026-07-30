import { NextResponse } from "next/server";
import { getCurrentUser } from "@/src/lib/helper/auth";

export async function GET() {
  try {
    const user = await getCurrentUser();

    if (!user) {
      return NextResponse.json(
        {
          success: false,
          status: 401,
          message: "Authentication Failed",
        },
        { status: 401 },
      );
    }

    return NextResponse.json(user);
  } catch (error) {
    console.error("GET /api/user:", error);

    return NextResponse.json(
      {
        success: false,
        status: 500,
        message:
          error instanceof Error ? error.message : "Internal Server Error",
      },
      { status: 500 },
    );
  }
}
