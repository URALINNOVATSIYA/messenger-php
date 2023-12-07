<?php

namespace Twin\Messenger\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

abstract class HttpClient implements Client
{
    protected string $baseUrl;
    private GuzzleClient $client;

    public function __construct(GuzzleClient $client, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    protected function request(
        string $method,
        string $url,
        array $params = [],
        array $headers = [],
        array $options = []
    ): ResponseInterface {
        $request = new Request($method, $url, $headers, $params);
        return $this->client->send($request, $options);
    }

    abstract protected function apiRequest(string $method, string $url, array $params = []): Response;
}