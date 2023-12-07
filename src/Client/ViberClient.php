<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class ViberClient extends HttpClient
{
    private string $accessToken;

    public function __construct(Credentials $credentials)
    {
        if (!$credentials->secretToken) {
            throw new Exception('Auth token is required for Viber integration');
        }
        $this->accessToken = $credentials->secretToken;
        parent::__construct(new GuzzleClient(), 'https://chatapi.viber.com/pa');
    }

    public function sendMessage(array $params): Response
    {
        return $this->apiRequest('POST', '/send_message', $params);
    }

    protected function apiRequest(string $method, string $url, array $params = []): Response
    {
        try {
            $apiResponse = $this->request(
                $method,
                $this->baseUrl.$url,
                $params,
                [
                    'X-Viber-Auth-Token' => $this->accessToken
                ]
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
        return $this->apiRequest('POST', '/set_webhook', [
            'url' => $webhookUrl
        ]);
    }
}