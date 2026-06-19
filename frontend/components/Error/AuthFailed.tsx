export default function AuthFailed(error: { message: string; status: number }) {
  return (
    <div className="flex flex-col items-center justify-center h-screen">
      <h1 className="text-4xl font-bold text-red-600">Authentication Failed</h1>
      <p className="mt-4 text-lg text-gray-700">{error.message}</p>
      <p className="mt-2 text-sm text-gray-500">Status Code: {error.status}</p>
    </div>
  );
}
