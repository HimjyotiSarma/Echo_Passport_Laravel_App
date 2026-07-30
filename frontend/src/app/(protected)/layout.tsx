import ProtectedLayoutSkeleton from "@skeletons/ProtectedLayoutSkeleton";
import { Suspense } from "react";
import ProtectedContent from "@/src/components/Layout/ProtectedContent";

export default function ProtectedLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <Suspense fallback={<ProtectedLayoutSkeleton />}>
      <ProtectedContent>{children}</ProtectedContent>
    </Suspense>
  );
}
