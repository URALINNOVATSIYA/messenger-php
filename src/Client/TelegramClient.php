<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Twin\Messenger\Auth\Credentials;

class TelegramClient extends HttpClient
{
    private ?string $botToken = null;

    public function __construct()
    {
        parent::__construct(new GuzzleClient(), 'https://api.telegram.org/bot');
    }

    public function auth(Credentials $credentials): void
    {
        $this->botToken = $credentials->secretToken;
    }

    private function getApiUrl(): string
    {
        return $this->baseUrl . $this->botToken;
    }

    public function sendMessage(array $params): Response
    {
        return $this->apiRequest('POST', '/sendMessage', $params);
    }

    protected function apiRequest(string $method, string $url, array $params = []): Response
    {
        try {
            $apiResponse = $this->request(
                $method,
                $this->getApiUrl() . $url,
                $params
            );

            $response = new Response(
                statusCode: $apiResponse->getStatusCode(),
                headers: $apiResponse->getHeaders(),
                body: $this->parseResponse($apiResponse),
            );
        } catch (GuzzleException $e) {
            $response = new Response(
                exception: $e
            );
        }

        return $response;
    }
}