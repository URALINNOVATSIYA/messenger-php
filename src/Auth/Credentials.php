<?php

namespace Twin\Messenger\Auth;

readonly class Credentials
{
    public ?string $secretToken;
    public ?string $accountId;

    public function __construct(
        ?string $secretToken = null,
        ?string $accountId = null,
    ) {
        $this->secretToken = $secretToken;
        $this->accountId = $accountId;
    }
}