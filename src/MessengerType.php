<?php

declare(strict_types=1);

namespace Twin\Messenger;

enum MessengerType
{
    case TELEGRAM;
    case WHATSAPP;
    case VIBER;
    case VKONTAKTE;
}