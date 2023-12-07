<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use Throwable;

readonly class Response
{
    public ?int $statusCode;
    public array $headers;
    public array $body;
    public string $error;
    public mixed $errorDetails;
    public ?Throwable $exception;

    public function __construct(
        ?int $statusCode = null,
        array $headers = [],
        array $body = [],
        string $error = '',
        mixed $errorDetails = null,
        ?Throwable $exception = null
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->error = $error;
        $this->errorDetails = $errorDetails;
        $this->exception = $exception;
    }

    public function hasError(): bool
    {
        return !!$this->error;
    }

    public function hasException(): bool
    {
        return !!$this->exception;
    }

    public function success(): bool
    {
        return !($this->hasError() || $this->hasException());
    }
}