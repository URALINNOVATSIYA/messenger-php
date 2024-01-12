<?php

declare(strict_types=1);

namespace Twin\Messenger\BotMessage;

class FileMessage extends BotMessage
{
    public string $url = '';
    public string $originalName = '';
    public string $downloadName = '';
    public int $size = 0;
    public string $mimeType = '';
    public string $thumbnail = '';
    public string $caption = '';
}