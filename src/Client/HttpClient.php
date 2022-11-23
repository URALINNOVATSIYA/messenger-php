<?php

namespace Twin\Messenger\Client;

abstract class HttpClient implements Client
{
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }
}