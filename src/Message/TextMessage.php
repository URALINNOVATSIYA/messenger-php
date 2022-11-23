<?php

declare(strict_types=1);

namespace Twin\Messenger\Message;

class TextMessage extends Message
{
    public string $body = '';

    /**
     * @var list<FileMessage>
     */
    public array $attachments = [];
}