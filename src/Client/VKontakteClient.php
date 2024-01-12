<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Twin\Messenger\Auth\Credentials;

class VKontakteClient extends HttpClient
{
    protected ?string $accessToken = null;

    public function __construct()
    {
        parent::__construct(new GuzzleClient(), 'https://api.vk.com/method');
    }

    public function auth(Credentials $credentials): void
    {
        $this->accessToken = $credentials->secretToken;
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
                $this->baseUrl . $url,
                $params,
                [
                    'access_token' => $this->accessToken
                ]
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