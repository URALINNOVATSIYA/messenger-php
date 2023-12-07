<?php

namespace Twin\Messenger;

readonly class MessengerConfig
{
    public ?string $webhookUrl;

    public function __construct(?string $webhookUrl = null)
    {
        $this->webhookUrl = $webhookUrl;
    }
}