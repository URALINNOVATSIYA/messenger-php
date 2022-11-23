<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

interface Client
{
    public function sendMessage(array $params): Response;
}