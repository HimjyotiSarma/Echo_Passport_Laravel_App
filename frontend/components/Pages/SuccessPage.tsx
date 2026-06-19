"use client";

import { useEffect } from "react";
import { useRouter } from "next/navigation";

export type SuccessPageProps = {
  redirectUrl: string;
  delay?: number;
  title?: string;
  message?: string;
};

export default function SuccessPage({
  redirectUrl,
  delay = 1000,
  title = "Success",
  message = "You will be redirected shortly.",
}: SuccessPageProps) {
  const router = useRouter();

  useEffect(() => {
    const timer = setTimeout(() => {
      router.replace(redirectUrl);
    }, delay);

    return () => clearTimeout(timer);
  }, [redirectUrl, delay, router]);

  return (
    <div className="flex min-h-screen flex-col items-center justify-center gap-6">
      <div className="h-12 w-12 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600" />

      <div className="text-center">
        <h1 className="text-3xl font-bold text-green-600">{title}</h1>

        <p className="mt-3 text-gray-600">{message}</p>
      </div>
    </div>
  );
}
