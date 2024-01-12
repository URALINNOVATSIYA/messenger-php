<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage\Entity;

class Button
{
    public ActionType $actionType = ActionType::NONE;
    public ?string $actionValue = null;
    public string $text = '';
    public string $bgImage = '';
    public string $bgColor = '';
}