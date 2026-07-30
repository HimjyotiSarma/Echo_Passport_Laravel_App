export default function ProtectedLayoutSkeleton() {
  return (
    <div className="min-h-screen bg-background">
      {/* Navbar */}
      <header className="border-b">
        <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
          <div className="h-8 w-32 animate-pulse rounded-md bg-muted" />

          <div className="flex items-center gap-4">
            <div className="h-9 w-24 animate-pulse rounded-md bg-muted" />
            <div className="h-10 w-10 animate-pulse rounded-full bg-muted" />
          </div>
        </div>
      </header>

      {/* Main */}
      <main className="mx-auto max-w-7xl p-6">
        <div className="mb-8 h-10 w-64 animate-pulse rounded bg-muted" />

        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          {Array.from({ length: 6 }).map((_, index) => (
            <div key={index} className="rounded-xl border p-6 shadow-sm">
              <div className="mb-4 h-5 w-1/2 animate-pulse rounded bg-muted" />
              <div className="mb-2 h-4 w-full animate-pulse rounded bg-muted" />
              <div className="mb-2 h-4 w-5/6 animate-pulse rounded bg-muted" />
              <div className="h-4 w-2/3 animate-pulse rounded bg-muted" />
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
