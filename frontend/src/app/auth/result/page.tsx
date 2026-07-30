import SuccessPage from "@/src/components/Pages/SuccessPage";
import { requireUser } from "@/src/lib/helper/auth";
import { connection } from "next/server";

export default async function AuthResultPage({
  searchParams,
}: {
  searchParams: Promise<{ returnTo?: string }>;
}) {
  await connection();
  const { returnTo = "/" } = await searchParams;

  // Redirects automatically if the user is not authenticated.
  await requireUser();

  return (
    <SuccessPage
      title="Authentication Successful"
      message="You will be redirected shortly."
      redirectUrl={returnTo}
      delay={1500}
    />
  );
}
