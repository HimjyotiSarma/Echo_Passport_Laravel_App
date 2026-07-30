"use client";

import AuthFailed from "@/src/components/Error/AuthFailed";

export default function Error({
  error,
  reset,
}: {
  error: Error;
  reset: () => void;
}) {
  return (
    <AuthFailed
      status={500}
      message={error.message || "Authentication failed."}
    />
  );
}
