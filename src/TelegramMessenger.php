<?php

namespace Twin\Messenger;

use RuntimeException;
use Twin\Messenger\Auth\Credentials;
use Twin\Messenger\BotMessage\AudioMessage;
use Twin\Messenger\BotMessage\BotMessage;
use Twin\Messenger\BotMessage\Entity\ActionType;
use Twin\Messenger\BotMessage\Entity\Button;
use Twin\Messenger\BotMessage\Entity\Keyboard;
use Twin\Messenger\BotMessage\FileMessage;
use Twin\Messenger\BotMessage\ImageMessage;
use Twin\Messenger\BotMessage\TextMessage;
use Twin\Messenger\BotMessage\VideoMessage;
use Twin\Messenger\Client\TelegramClient;
use Twin\Messenger\UserMessage\UserMessage;

class TelegramMessenger extends Messenger
{
    public function __construct(TelegramClient $client)
    {
        parent::__construct($client);
    }

    public function authenticate(Credentials $credentials): void
    {
        if (!$credentials->secretToken) {
            throw new RuntimeException('Bot token is required for Telegram integration');
        }
        parent::authenticate($credentials);
    }

    public function receiveMessage(array $input): UserMessage
    {
        // TODO: Implement receiveMessage() method.
    }

    protected function sendTextMessage(string $userId, TextMessage $message)
    {
        $params = [
            'chat_id' => $userId,
            'text' => $message->body,
            'parse_mode' => 'HTML',
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
            'chat_id' => $userId,
            'photo' => $message->url,
            'parse_mode' => 'HTML'
        ];
        if ($message->caption) {
            $params['caption'] = $message->caption;
        }
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    protected function sendVideoMessage(string $userId, VideoMessage $message)
    {
        $params = [
            'chat_id' => $userId,
            'video' => $message->url,
            'parse_mode' => 'HTML'
        ];
        if ($message->duration) {
            $params['duration'] = $message->duration;
        }
        if ($message->width) {
            $params['width'] = $message->width;
        }
        if ($message->height) {
            $params['height'] = $message->height;
        }
        if ($message->thumbnail) {
            $params['thumb'] = $message->thumbnail;
        }
        if ($message->caption) {
            $params['caption'] = $message->caption;
        }
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    protected function sendAudioMessage(string $userId, AudioMessage $message)
    {
        $params = [
            'chat_id' => $userId,
            'audio' => $message->url,
            'parse_mode' => 'HTML'
        ];
        if ($message->duration) {
            $params['duration'] = $message->duration;
        }
        if ($message->performer) {
            $params['performer'] = $message->performer;
        }
        if ($message->title) {
            $params['title'] = $message->title;
        }
        if ($message->thumbnail) {
            $params['thumb'] = $message->thumbnail;
        }
        if ($message->caption) {
            $params['caption'] = $message->caption;
        }
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    protected function sendFileMessage(string $userId, FileMessage $message)
    {
        $params = [
            'chat_id' => $userId,
            'document' => $message->url,
            'parse_mode' => 'HTML'
        ];
        if ($message->thumbnail) {
            $params['thumb'] = $message->thumbnail;
        }
        if ($message->caption) {
            $params['caption'] = $message->caption;
        }
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
    }

    private function addGeneralParameters(array &$params, BotMessage $message): void
    {
        if ($message->replyToMessageId) {
            $params['reply_to_message_id'] = $message->replyToMessageId;
            $params['allow_sending_without_reply'] = true;
        }
        if ($message->keyboard) {
            $this->addKeyboardParameters($params, $message->keyboard);
        }
    }

    private function addKeyboardParameters(array &$params, Keyboard $keyboard): void
    {
        if ($keyboard->remove || !$keyboard->buttons) {
            $params['reply_markup'] = json_encode(['remove_keyboard' => true]);
            return;
        }
        $buttons = [];
        $isInline = $keyboard->inline;
        foreach ($keyboard->buttons as $rows) {
            $row = [];
            foreach ($rows as $button) {
                if ($button->actionType === ActionType::OPEN_URL ||
                    $button->actionValue === ActionType::CALLBACK
                ) {
                    $isInline = true;
                }
                $row[] = $this->extractButtonParameters($button);
            }
            $buttons[] = $row;
        }
        if ($isInline) {
            $kb = [
                'inline_keyboard' => $buttons
            ];
        } else {
            $kb = [
                'keyboard' => $buttons,
                'resize_keyboard' => $keyboard->resize,
                'one_time_keyboard' => $keyboard->oneTime
            ];
        }
        $params['reply_markup'] = json_encode($kb);
    }

    private function extractButtonParameters(Button $button): array
    {
        $params = [
            'text' => $button->text
        ];
        switch ($button->actionType) {
            case ActionType::OPEN_URL:
                $params['url'] = $button->actionValue;
                return $params;
            case ActionType::CALLBACK:
                $params['callback_data'] = $button->actionValue;
                return $params;
            case ActionType::OPEN_APP:
                $params['web_app'] = ['url' => $button->actionValue];
                return $params;
            case ActionType::REQUEST_CONTACT:
                $params['request_contact'] = true;
                return $params;
            default:
                return $params;
        }
    }
}