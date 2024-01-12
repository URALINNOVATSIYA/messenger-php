<?php

namespace Twin\Messenger;

use Exception;
use Twin\Messenger\Auth\Credentials;
use Twin\Messenger\Client\Client;
use Twin\Messenger\Message\AudioMessage;
use Twin\Messenger\Message\FileMessage;
use Twin\Messenger\Message\ImageMessage;
use Twin\Messenger\Message\TextMessage;
use Twin\Messenger\Message\VideoMessage;

class WhatsappMessenger extends Messenger
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function parseIncomingMessage(array $input)
    {
        // TODO: Implement parseIncomingMessage() method.
    }

    public function authenticate(Credentials $credentials): void
    {
        if (!$credentials->secretToken || !$credentials->accountId) {
            throw new Exception('Auth token and account ID are required for Whatsapp integration');
        }
        parent::authenticate($credentials);
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