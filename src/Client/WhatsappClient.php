<?php

namespace Twin\Messenger\Client;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class WhatsappClient extends HttpClient
{
    private string $accountId;
    private string $authToken;

    public function __construct(Credentials $credentials)
    {
        if (!$credentials->secretToken || !$credentials->accountId) {
            throw new Exception('Auth token is required for Whatsapp integration');
        }
        $this->accountId = $credentials->accountId;
        $this->authToken = $credentials->secretToken;
        parent::__construct(new GuzzleClient(), "https://api-whatsapp.io/api");
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
                $this->baseUrl.'/'.$this->accountId.$url.'?'.$queryParams,
                $params,
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
            exception: new Exception('Whatsapp doesn\'t support webhooks')
        );
    }
}