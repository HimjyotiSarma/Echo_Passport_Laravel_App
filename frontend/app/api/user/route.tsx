import { cookies } from "next/headers";
export async function GET() {
  try {
    const cookieStore = await cookies();
    const response = await fetch(`${process.env.BACKEND_URL}/api/user`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Cookie: cookieStore.toString(),
      },
      //   credentials: "include",
      cache: "no-store",
    });
    if (!response.ok) {
      throw new Error(`Failed to fetch user data: ${response.statusText}`);
    }
    const userData = await response.json();
    return new Response(JSON.stringify(userData), {
      status: 200,
      headers: {
        "Content-Type": "application/json",
      },
    });
  } catch (error: Error | unknown) {
    return new Response(
      JSON.stringify({
        status: "error",
        message:
          error instanceof Error
            ? error.message
            : "An Error occurred while processing the request.",
      }),
      {
        status: 500,
      },
    );
  }
}
