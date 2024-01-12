<?php

declare(strict_types=1);

namespace Twin\Messenger\UserMessage;

class VideoMessage extends FileMessage
{
    public int $width = 0;
    public int $height = 0;
    public int $duration = 0;
}