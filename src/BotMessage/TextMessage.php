<?php

declare(strict_types=1);

namespace Twin\Messenger\BotMessage;

class TextMessage extends BotMessage
{
    public string $body = '';

    /**
     * @var list<FileMessage>
     */
    public array $attachments = [];
}