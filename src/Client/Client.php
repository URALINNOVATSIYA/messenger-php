<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use Twin\Messenger\Auth\Credentials;

interface Client
{
    public function sendMessage(array $params): Response;

    public function auth(Credentials $credentials): void;
}