<?php

namespace Twin\Messenger;

use Twin\Messenger\Client\Client;
use Twin\Messenger\Message\AudioMessage;
use Twin\Messenger\Message\FileMessage;
use Twin\Messenger\Message\ImageMessage;
use Twin\Messenger\Message\TextMessage;
use Twin\Messenger\Message\VideoMessage;

class WhatsappMessenger extends Messenger
{
    public function __construct(MessengerConfig $config, Client $client)
    {
        parent::__construct($config, $client);
    }

    public function parseIncomingMessage(array $input)
    {
        // TODO: Implement parseIncomingMessage() method.
    }

    protected function sendTextMessage(string $userId, TextMessage $message)
    {
        // TODO: Implement sendTextMessage() method.
    }

    protected function sendImageMessage(string $userId, ImageMessage $message)
    {
        // TODO: Implement sendImageMessage() method.
    }

    protected function sendVideoMessage(string $userId, VideoMessage $message)
    {
        // TODO: Implement sendVideoMessage() method.
    }

    protected function sendAudioMessage(string $userId, AudioMessage $message)
    {
        // TODO: Implement sendAudioMessage() method.
    }

    protected function sendFileMessage(string $userId, FileMessage $message)
    {
        // TODO: Implement sendFileMessage() method.
    }
}