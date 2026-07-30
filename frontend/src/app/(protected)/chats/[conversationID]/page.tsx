export default async function ConversationWindow({
  params,
}: {
  params: Promise<{ conversationID: string }>;
}) {
  const { conversationID } = await params;
  return <h1>The requested Conversation is {conversationID}</h1>;
}
