<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage;

class TextMessage extends UserMessage
{
    public string $body = '';

    /**
     * @var list<FileMessage>
     */
    public array $attachments = [];
}