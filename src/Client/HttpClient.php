<?php

namespace Twin\Messenger\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use OutOfBoundsException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class HttpClient implements Client
{
    protected string $baseUrl;
    private int $requestTimeout = 60;
    private int $connectionTimeout = 5;
    private string $defaultContentType = 'application/json';
    protected ?Throwable $lastException = null;

    private GuzzleClient $client;

    public function __construct(GuzzleClient $client, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    public function setConnectionTimeout(int $timeout): static
    {
        $this->connectionTimeout = $timeout;
        return $this;
    }

    public function getConnectionTimeout(): int
    {
        return $this->connectionTimeout;
    }

    public function setRequestTimeout(int $timeout): static
    {
        $this->requestTimeout = $timeout;
        return $this;
    }

    public function getRequestTimeout(): int
    {
        return $this->requestTimeout;
    }

    /**
     * @param array<string, string> $headers
     * @return void
     */
    private function addDefaultContentTypeHeader(array &$headers): void
    {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = $this->defaultContentType;
        }
    }

    protected function request(
        string $method,
        string $url,
        array $params = [],
        array $headers = [],
        array $options = []
    ): ResponseInterface {
        $this->lastException = null;
        $request = $this->getRequest($method, $url, $headers, $params);
        try {
            $response = $this->client->send(
                $request,
                array_merge(
                    $this->getRequestOptions($method, $url, $params, $headers),
                    $options
                )
            );
        } catch (Throwable $e) {
            $this->lastException = $e;
            throw $e;
        }

        return $response;
    }

    protected function parseResponse(ResponseInterface $response): array
    {
        $responseHeaders = $response->getHeaders();
        $response = $response->getBody()->getContents();

        $contentType = $responseHeaders['content-type'] ?? '';
        if ($contentType === '') {
            return [$response];
        }

        $contentType = explode(';', $contentType)[0];
        if ($contentType === 'application/json' || str_ends_with($contentType, 'json')) {
            return json_decode($response, true) ?? [$response];
        }

        return [$response];
    }

    private function getRequest(
        string $method,
        string $url,
        array $headers = null,
        array $params = null
    ): Request {
        $this->addDefaultContentTypeHeader($headers);

        $paramsKey = $this->getKeyOfParamsOption($method, $headers);
        if ($paramsKey === RequestOptions::QUERY) {
            $params = array_merge($params, $this->extractQueryParams($url));
        }

        /**
         * https://github.com/guzzle/guzzle/issues/1885
         * Because guzzle incorrect works with manually set Content-Type header (didn't add boundary), we should remove it before sending         *
         */
        if ($paramsKey === RequestOptions::MULTIPART) {
            unset($headers['Content-Type']);
        }

        return new Request($method, $url, $headers, $params);
    }

    /**
     * @param string $method
     * @param array<string, string> $headers
     * @return string
     */
    private function getKeyOfParamsOption(string $method, array $headers): string
    {
        if ($method === 'GET') {
            return RequestOptions::QUERY;
        }
        return match ($contentType = strtolower($headers['Content-Type'])) {
            'application/json' => RequestOptions::JSON,
            'multipart/form-data' => RequestOptions::MULTIPART,
            'application/x-www-form-urlencoded' => RequestOptions::FORM_PARAMS,
            default => throw new OutOfBoundsException("Content type \"$contentType\" is not supported.")
        };
    }

    private function extractQueryParams(string $url): array
    {
        parse_str((string)parse_url($url, PHP_URL_QUERY), $params);
        return $params;
    }

    private function getRequestOptions(string $method, string $url, array $params, array $headers = []): array
    {
        $this->addDefaultContentTypeHeader($headers);

        $paramsKey = $this->getKeyOfParamsOption($method, $headers);
        if ($method === 'GET') {
            $params = array_merge($params, $this->extractQueryParams($url));
        }

        /**
         * https://github.com/guzzle/guzzle/issues/1885
         * Because guzzle incorrect works with manually set Content-Type header (didn't add boundary), we should remove it before sending         *
         */
        if ($paramsKey === RequestOptions::MULTIPART) {
            unset($headers['Content-Type']);
        }

        return [
            'http_errors' => false,
            'connect_timeout' => $this->connectionTimeout,
            'timeout' => $this->requestTimeout,
            'headers' => $headers,
            $paramsKey => $params,
        ];
    }

    abstract protected function apiRequest(string $method, string $url, array $params = []): Response;
}