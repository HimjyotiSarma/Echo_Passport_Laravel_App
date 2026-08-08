<?php

namespace App\Enums;

enum MessageType: string
{
    case TEXT = 'text';
    case SYSTEM = 'system';
}
