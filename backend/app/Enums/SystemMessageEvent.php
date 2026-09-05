<?php

namespace App\Enums;

enum SystemMessageEvent: string
{
    case PARTICIPANT_ADDED = 'participant_added';
    case PARTICIPANT_REMOVED = 'participant_removed';
    case PARTICIPANT_ROLE_CHANGED = 'particiapant_role_changed';
    case CONVERSATION_UPDATED = 'conversation_updated';
}
