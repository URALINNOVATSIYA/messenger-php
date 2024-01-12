<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage;

class FileMessage extends UserMessage
{
    public string $url = '';
    public string $originalName = '';
    public string $downloadName = '';
    public int $size = 0;
    public string $mimeType = '';
    public string $thumbnail = '';
    public string $caption = '';
}