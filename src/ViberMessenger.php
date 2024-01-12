<?php

declare(strict_types=1);

namespace Twin\Messenger;

use RuntimeException;
use Twin\Messenger\Auth\Credentials;
use Twin\Messenger\BotMessage\BotMessage;
use Twin\Messenger\Client\ViberClient;
use Twin\Messenger\UserMessage\AudioMessage;
use Twin\Messenger\UserMessage\Entity\ActionType;
use Twin\Messenger\UserMessage\Entity\Button;
use Twin\Messenger\UserMessage\Entity\Keyboard;
use Twin\Messenger\UserMessage\Entity\Sender;
use Twin\Messenger\UserMessage\FileMessage;
use Twin\Messenger\UserMessage\ImageMessage;
use Twin\Messenger\UserMessage\TextMessage;
use Twin\Messenger\UserMessage\UserMessage;
use Twin\Messenger\UserMessage\VideoMessage;

class ViberMessenger extends Messenger
{
    public function __construct(ViberClient $client)
    {
        parent::__construct($client);
    }

    public function authenticate(Credentials $credentials): void
    {
        if (!$credentials->secretToken) {
            throw new RuntimeException('Auth token is required for Viber integration');
        }
        parent::authenticate($credentials);
    }

    public function receiveMessage(array $input): BotMessage
    {
        // TODO: Implement receiveMessage() method.
    }

    protected function sendTextMessage(string $userId, TextMessage $message)
    {
        $params = [
            'type' => 'text',
            'receiver' => $userId,
            'text' => $message->body
        ];
        $this->addGeneralParameters($params, $message);
        $response = $this->client->sendMessage($params);
        foreach ($message->attachments as $attachment) {
            $response = $this->sendFileMessage($userId, $attachment);
        }
        return $response;
    }

    protected function sendImageMessage(string $userId, ImageMessage $message)
    {
        $params = [
            'type' => 'picture',
            'receiver' => $userId,
            'text' => $message->caption,
            'media' => $message->url
        ];
        if ($message->size) {
            $params['size'] = $message->size;
        }
        if ($message->downloadName) {
            $params['file_name'] = $message->downloadName;
        }
        if ($message->thumbnail) {
            $params['thumbnail'] = $message->thumbnail;
        }
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    protected function sendVideoMessage(string $userId, VideoMessage $message)
    {
        $params = [
            'type' => 'video',
            'receiver' => $userId,
            'media' => $message->url,
            'size' => $message->size,
        ];
        if ($message->duration) {
            $params['duration'] = $message->duration;
        }
        if ($message->thumbnail) {
            $params['thumbnail'] = $message->thumbnail;
        }
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    protected function sendAudioMessage(string $userId, AudioMessage $message)
    {
        return $this->sendFileMessage($userId, $message);
    }

    protected function sendFileMessage(string $userId, FileMessage $message)
    {
        $params = [
            'type' => 'file',
            'receiver' => $userId,
            'media' => $message->url,
            'size' => $message->size,
            'file_name' => $message->downloadName
        ];
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    private function addGeneralParameters(array &$params, UserMessage $message): void
    {
        if ($message->trackingData !== null) {
            $params['tracking_data'] = $message->trackingData;
        }
        if ($sender = $message->sender) {
            $this->addSenderParameters($params, $sender);
        }
        if ($keyboard = $message->keyboard) {
            $this->addKeyboardParameters($params, $keyboard);
        }
    }

    private function addSenderParameters(array &$params, Sender $sender): void
    {
        if ($sender->name) {
            $params['sender.name'] = $sender->name;
        }
        if ($sender->avatar) {
            $params['sender.avatar'] = $sender->avatar;
        }
    }

    private function addKeyboardParameters(array &$params, Keyboard $keyboard): void
    {
        $buttons = [];
        foreach ($keyboard->buttons as $rows) {
            foreach ($rows as $button) {
                $buttons[] = $this->extractButtonParameters($button);
            }
        }
        $kb = [
            'Type' => 'keyboard',
            'DefaultHeight' => $keyboard->defaultHeight,
            'Buttons' => $buttons
        ];
        if ($keyboard->bgColor) {
            $kb['BgColor'] = $keyboard->bgColor;
        }
        $params['keyboard'] = $kb;
    }

    private function extractButtonParameters(Button $button): array
    {
        $params = [
            'ActionBody' => match ($button->actionType) {
                ActionType::OPEN_URL => $button->actionValue,
                default => $button->actionValue ?? $button->text,
            },
            'ActionType' => match ($button->actionType) {
                ActionType::NONE => 'none',
                ActionType::OPEN_URL => 'open-url',
                default => 'reply',
            },
        ];
        if ($button->text) {
            $params['Text'] = $button->text;
        }
        if ($button->bgColor) {
            $params['BgColor'] = $button->bgColor;
        }
        if ($button->bgImage) {
            $params['Image'] = $button->bgImage;
        }
        return $params;
    }
}