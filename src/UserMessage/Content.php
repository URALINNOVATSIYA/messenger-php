<?php

namespace Twin\Messenger\UserMessage;

class Content
{
    public string $body;
    public array $attachments;

    public function __construct(
        string $body,
        array $attachments = [],
    ) {
        $this->body = $body;
        $this->attachments = $attachments;
    }
}