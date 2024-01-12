<?php

declare(strict_types=1);

namespace Twin\Messenger\BotMessage;

use Twin\Messenger\BotMessage\Entity\Keyboard;
use Twin\Messenger\BotMessage\Entity\Sender;

abstract class BotMessage
{
    public string $id = '';
    public ?string $replyToMessageId = null;
    public ?string $trackingData = null;
    public ?Sender $sender = null;
    public ?Keyboard $keyboard = null;
}