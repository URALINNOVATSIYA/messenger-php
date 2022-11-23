<?php

declare(strict_types=1);

namespace Twin\Messenger;

use Twin\Messenger\Client\Client;
use Twin\Messenger\Client\TelegramClient;
use Twin\Messenger\Client\ViberClient;
use Twin\Messenger\Client\VKontakteClient;
use Twin\Messenger\Message\AudioMessage;
use Twin\Messenger\Message\FileMessage;
use Twin\Messenger\Message\ImageMessage;
use Twin\Messenger\Message\Message;
use Twin\Messenger\Message\TextMessage;
use Twin\Messenger\Message\VideoMessage;

abstract class Messenger
{
    protected Client $client;

    public static function from(MessengerType $type): static
    {
        match ($type) {
            MessengerType::VIBER => new static(new ViberClient()),
            MessengerType::TELEGRAM => new static(new TelegramClient()),
            MessengerType::VKONTAKTE => new static(new VKontakteClient()),
        };
    }

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    abstract public function parseIncomingMessage(array $input);

    final public function sendMessage(string $userId, Message $message)
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
        throw new \UnexpectedValueException('Unsupported message of type ' . $message::class);
    }

    abstract protected function sendTextMessage(string $userId, TextMessage $message);

    abstract protected function sendImageMessage(string $userId, ImageMessage $message);

    abstract protected function sendVideoMessage(string $userId, VideoMessage $message);

    abstract protected function sendAudioMessage(string $userId, AudioMessage $message);

    abstract protected function sendFileMessage(string $userId, FileMessage $message);
}