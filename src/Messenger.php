<?php

declare(strict_types=1);

namespace Twin\Messenger;

use Twin\Messenger\Auth\Credentials;
use Twin\Messenger\BotMessage\BotMessage;
use Twin\Messenger\Client\Client;
use Twin\Messenger\Client\TelegramClient;
use Twin\Messenger\Client\ViberClient;
use Twin\Messenger\Client\VKontakteClient;
use Twin\Messenger\Client\WhatsappClient;
use Twin\Messenger\UserMessage\AudioMessage;
use Twin\Messenger\UserMessage\FileMessage;
use Twin\Messenger\UserMessage\ImageMessage;
use Twin\Messenger\UserMessage\TextMessage;
use Twin\Messenger\UserMessage\UserMessage;
use Twin\Messenger\UserMessage\VideoMessage;
use UnexpectedValueException;

abstract class Messenger
{
    protected Client $client;

    public static function from(MessengerType $type): static
    {
        return match ($type) {
            MessengerType::VIBER => new static(new ViberClient()),
            MessengerType::TELEGRAM => new static(new TelegramClient()),
            MessengerType::VKONTAKTE => new static(new VKontakteClient()),
            MessengerType::WHATSAPP => new static(new WhatsappClient()),
        };
    }

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authenticate(Credentials $credentials): void
    {
        $this->client->auth($credentials);
    }

    abstract public function receiveMessage(array $input): BotMessage;

    final public function sendMessage(string $userId, UserMessage $message)
    {
        if ($message instanceof TextMessage) {
            return $this->sendTextMessage($userId, $message);
        }
        if ($message instanceof ImageMessage) {
            return $this->sendImageMessage($userId, $message);
        }
        if ($message instanceof VideoMessage) {
            return $this->sendVideoMessage($userId, $message);
        }
        if ($message instanceof AudioMessage) {
            return $this->sendAudioMessage($userId, $message);
        }
        if ($message instanceof FileMessage) {
            return $this->sendFileMessage($userId, $message);
        }
        throw new UnexpectedValueException('Unsupported message of type ' . $message::class);
    }

    abstract protected function sendTextMessage(string $userId, TextMessage $message);

    abstract protected function sendImageMessage(string $userId, ImageMessage $message);

    abstract protected function sendVideoMessage(string $userId, VideoMessage $message);

    abstract protected function sendAudioMessage(string $userId, AudioMessage $message);

    abstract protected function sendFileMessage(string $userId, FileMessage $message);
}