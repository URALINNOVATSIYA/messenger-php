<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class VKontakteClient extends HttpClient
{
    protected ?string $accessToken;

    public function __construct(Credentials $credentials)
    {
        if (!$credentials->secretToken) {
            throw new Exception('Auth token is required for Vkontakte integration');
        }
        $this->accessToken = $credentials->secretToken;
        parent::__construct(new GuzzleClient(), 'https://api.vk.com/method');
    }

    public function sendMessage(array $params): Response
    {
        return $this->apiRequest('POST', '/messages.send', $params);
    }

    protected function apiRequest(string $method, string $url, array $params = []): Response
    {
        try {
            $apiResponse = $this->request(
                $method,
                $this->baseUrl.$url,
                $params,
                [
                    'access_token' => $this->accessToken
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
        return new Response(
            exception: new Exception('Vkontakte doesn\'t support webhooks')
        );
    }
}