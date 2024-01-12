<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage\Entity;

enum ActionType
{
    case NONE;
    case REPLY;
    case OPEN_URL;
    case OPEN_APP;
    case CALLBACK;
    case REQUEST_CONTACT;
}