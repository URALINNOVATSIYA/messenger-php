<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

class TelegramClient extends HttpClient
{
    public function __construct()
    {
        parent::__construct('https://api.telegram.org/bot');
    }

    public function sendMessage(array $params): Response
    {
        // TODO: Implement sendMessage() method.
    }
}