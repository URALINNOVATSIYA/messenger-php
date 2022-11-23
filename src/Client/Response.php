<?php

declare(strict_types=1);

namespace Twin\Messenger\Client;

use Throwable;

class Response
{
    public int $statusCode;
    public array $headers;
    public array $body;
    public string $error;
    public mixed $errorDetails;
    public ?Throwable $exception;
}