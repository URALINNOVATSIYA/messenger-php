<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage\Entity;

class Keyboard
{
    /**
     * @var list<list<Button>>
     */
    public array $buttons = [];
    public bool $inline = false;
    public bool $remove = false;
    public bool $oneTime = false;
    public bool $resize = false;
    public bool $defaultHeight = false;
    public string $bgColor = '';
}