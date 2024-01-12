<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage;

use Twin\Messenger\UserMessage\Entity\Keyboard;
use Twin\Messenger\UserMessage\Entity\Sender;

abstract class UserMessage
{
    public string $id = '';
    public ?string $replyToMessageId = null;
    public ?string $trackingData = null;
    public ?Sender $sender = null;
    public ?Keyboard $keyboard = null;
}