<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class TelegramClient extends HttpClient
{
    private string $botToken;

    public function __construct(Credentials $credentials)
    {
        if (!$credentials->secretToken) {
            throw new Exception('Bot token is required for Telegram  integration');
        }
        $this->botToken = $credentials->secretToken;
        parent::__construct(new GuzzleClient(), 'https://api.telegram.org/bot');
    }

    private function getApiUrl(): string
    {
        return $this->baseUrl.$this->botToken;
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
                $this->getApiUrl().$url,
                $params
            );

            $response = new Response(
                statusCode: $apiResponse->getStatusCode(),
                headers: $apiResponse->getHeaders(),
                body: json_decode($apiResponse->getBody()->getContents(), true),
            );
        } catch (GuzzleException $e) {
            $response = new Response(
                exception: $e
            );
        }

        return $response;
    }

    public function setWebhook(string $webhookUrl): Response
    {
        return $this->apiRequest('POST', '/setWebhook', [
            'url' => $webhookUrl
        ]);
    }
}