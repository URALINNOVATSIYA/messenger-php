<?php

namespace Twin\Messenger\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Twin\Messenger\Auth\Credentials;

class WhatsappClient extends HttpClient
{
    private ?string $accountId = null;
    private ?string $authToken = null;

    public function __construct()
    {
        parent::__construct(new GuzzleClient(), "https://api-whatsapp.io/api");
    }

    public function auth(Credentials $credentials): void
    {
        $this->accountId = $credentials->accountId;
        $this->authToken = $credentials->secretToken;
    }

    public function sendMessage(array $params): Response
    {
        return $this->apiRequest('POST', '/sendMessage', $params);
    }

    protected function apiRequest(string $method, string $url, array $params = []): Response
    {
        $queryParams = http_build_query([
            'token' => $this->authToken,
        ]);
        try {
            $apiResponse = $this->request(
                $method,
                $this->baseUrl . '/' . $this->accountId . $url . '?' . $queryParams,
                $params,
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