<?php

namespace Twin\Messenger\UserMessage;

use DateTimeImmutable;

readonly class UserMessage
{
    public string $id;
    public ?string $chatId;
    public User $user;
    public Content $content;
    public DateTimeImmutable $createdAt;
    public ?string $replyToMessageId;
    public bool $isEdited;
    public bool $isDeleted;

    public function __construct(
        string $id,
        User $user,
        Content $content,
        DateTimeImmutable $createdAt,
        ?string $chatId = null,
        ?string $replyToMessageId = null,
        bool $isEdited = false,
        bool $isDeleted = false,
    ) {
        $this->id = $id;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->replyToMessageId = $replyToMessageId;
        $this->isEdited = $isEdited;
        $this->isDeleted = $isDeleted;
    }
}