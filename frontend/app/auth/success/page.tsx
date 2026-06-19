import AuthFailed from "@/components/Error/AuthFailed";
import { cookies } from "next/headers";
import { UserData } from "@/Types/Responses";
import SuccessPage from "@/components/Pages/SuccessPage";
export default async function AuthSuccessPage() {
  const cookieStore = await cookies();
  const response = await fetch(`${process.env.NEXT_PUBLIC_BASE_URL}/api/user`, {
    method: "GET",
    headers: {
      Cookie: cookieStore.toString(),
    },
    cache: "no-store",
  });
  if (!response.ok) {
    return (
      <AuthFailed
        message={"Failed to fetch user data"}
        status={response.status}
      />
    );
  }
  const userData: UserData = await response.json();
  cookieStore.set(
    "user_data",
    JSON.stringify({
      id: userData.id,
      name: userData.name,
      email: userData.email,
      role: userData.role,
    }),
    {
      path: "/",
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      maxAge: 300,
    },
  );
  return (
    <SuccessPage
      title="Authentication Successful"
      message="You will be redirected to the Home page shortly."
      redirectUrl="/dashboard"
      delay={1500}
    />
  );
}
