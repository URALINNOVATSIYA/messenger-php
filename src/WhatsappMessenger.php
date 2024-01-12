<?php

namespace Twin\Messenger;

use RuntimeException;
use Twin\Messenger\Auth\Credentials;
use Twin\Messenger\BotMessage\BotMessage;
use Twin\Messenger\Client\Client;
use Twin\Messenger\UserMessage\AudioMessage;
use Twin\Messenger\UserMessage\FileMessage;
use Twin\Messenger\UserMessage\ImageMessage;
use Twin\Messenger\UserMessage\TextMessage;
use Twin\Messenger\UserMessage\VideoMessage;

class WhatsappMessenger extends Messenger
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function authenticate(Credentials $credentials): void
    {
        if (!$credentials->secretToken || !$credentials->accountId) {
            throw new RuntimeException('Auth token and account ID are required for Whatsapp integration');
        }
        parent::authenticate($credentials);
    }

    public function receiveMessage(array $input): BotMessage
    {
        // TODO: Implement receiveMessage() method.
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