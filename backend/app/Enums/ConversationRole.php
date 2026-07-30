<?php

namespace App\Enums;

enum ConversationRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MEMBER = 'member';
}
