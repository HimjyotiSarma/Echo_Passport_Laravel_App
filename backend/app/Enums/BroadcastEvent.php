<?php

namespace App\Enums;

enum BroadcastEvent: string
{
    case CONVERSATION_UPDATED = 'conversation.updated';
    case CONVERSATION_PARTICIPANT_ADDED = 'conversation.participant.added';
    case CONVERSATION_PARTICIPANT_UPDATED = 'conversation.participant.updated';
    case CONVERSATION_PARTICIPANT_REMOVED = 'conversation.participant.removed';

    case MESSAGE_CREATED = 'message.created';
    case MESSAGE_UPDATED = 'message.updated';
    case MESSAGE_DELETED = 'message.deleted';

    case REACTION_CREATED = 'reaction.created';
    case REACTION_UPDATED = 'reaction.updated';
    case REACTION_DELETED = 'reaction.deleted';
}
