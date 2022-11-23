<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

class VKontakteClient extends HttpClient
{
    public function __construct()
    {
        parent::__construct('https://api.vk.com/method');
    }

    public function sendMessage(array $params): Response
    {
        // TODO: Implement sendMessage() method.
    }
}