<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Twin\Messenger\Auth\Credentials;

class ViberClient extends HttpClient
{
    private ?string $accessToken = null;

    public function __construct()
    {
        parent::__construct(new GuzzleClient(), 'https://chatapi.viber.com/pa');
    }

    public function auth(Credentials $credentials): void
    {
        $this->accessToken = $credentials->secretToken;
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
                $this->baseUrl . $url,
                $params,
                [
                    'X-Viber-Auth-Token' => $this->accessToken
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