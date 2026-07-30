"use client";
import { useUserStore } from "@/src/providers/user-store-provider";

export default function ProfilePage() {
  const name = useUserStore((state) => state.name);
  return (
    <>
      <h1>Hello, this is a Profile Page</h1>
      <p>The current User name is {name}</p>
    </>
  );
}
