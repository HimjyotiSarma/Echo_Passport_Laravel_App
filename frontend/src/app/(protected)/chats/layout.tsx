import NavBar from "@/src/components/ui/Header/Navbar";

export default function ChatLayout({
  children,
  contacts,
}: {
  children: React.ReactNode;
  contacts: React.ReactNode;
}) {
  return (
    <div className="flex h-screen">
      <aside className="w-80 border-r">{contacts}</aside>
      <main className="flex-1">{children}</main>
    </div>
  );
}
