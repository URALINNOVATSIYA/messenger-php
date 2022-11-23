<?php

declare(strict_types=1);

namespace Twin\Messenger\Message;

use Twin\Messenger\Message\Entity\Keyboard;
use Twin\Messenger\Message\Entity\Sender;

abstract class Message
{
    public string $id = '';
    public ?string $replyToMessageId = null;
    public ?string $trackingData = null;
    public ?Sender $sender = null;
    public ?Keyboard $keyboard = null;
}