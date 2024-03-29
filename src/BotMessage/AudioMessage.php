<?php

declare(strict_types=1);

namespace Twin\Messenger\BotMessage;

class AudioMessage extends FileMessage
{
    public string $title = '';
    public int $duration = 0;
    public string $performer = '';
}