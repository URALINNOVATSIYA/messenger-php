<?php

declare(strict_types=1);

namespace Twin\Messenger\Message;

class ImageMessage extends FileMessage
{
    public int $width = 0;
    public int $height = 0;
}