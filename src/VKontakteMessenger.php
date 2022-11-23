<?php

declare(strict_types=1);

namespace Twin\Messenger;

use Twin\Messenger\Client\VKontakteClient;
use Twin\Messenger\Message\AudioMessage;
use Twin\Messenger\Message\Entity\ActionType;
use Twin\Messenger\Message\Entity\Button;
use Twin\Messenger\Message\Entity\Keyboard;
use Twin\Messenger\Message\FileMessage;
use Twin\Messenger\Message\ImageMessage;
use Twin\Messenger\Message\Message;
use Twin\Messenger\Message\TextMessage;
use Twin\Messenger\Message\VideoMessage;

class VKontakteMessenger extends Messenger
{
    public function __construct(VKontakteClient $client)
    {
        parent::__construct($client);
    }

    public function parseIncomingMessage(array $input)
    {

    }

    protected function sendTextMessage(string $userId, TextMessage $message)
    {
        $params = [
            'peer_id' => $userId,
            'message' => $message->body
        ];
        $this->addAttachments($params, $userId, $message->attachments);
        $this->addGeneralParameters($params, $message);
        return $this->client->sendMessage($params);
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

    /**
     * @param array $params
     * @param list<FileMessage> $attachments
     * @return void
     */
    private function addAttachments(array &$params, string $userId, array $attachments)
    {
        if (!$attachments) {
            return;
        }
        $ids = [];
        foreach ($attachments as $attachment) {
            if ($this->isAudio($attachment->mimeType)) {
                $ids[] = $this->client->uploadAudio($attachment->url, $attachment->downloadName);
            } else if ($this->isVideo($attachment->mimeType)) {
                $ids[] = $this->client->uploadVideo($attachment->url, $attachment->downloadName);
            } else if ($this->isPhoto($attachment->mimeType)) {
                $ids[] = $this->client->uploadPhoto($attachment->url, $attachment->downloadName);
            } else {
                $ids[] = $this->client->uploadDocument($attachment->url, $attachment->downloadName);
            }
        }
        $params['attachments'] = implode(',', $ids);
    }

    private function isPhoto(string $contentType): bool
    {
        return str_starts_with($contentType, 'image');
    }

    private function isAudio(string $contentType): bool
    {
        return str_starts_with($contentType, 'audio');
    }

    private function isVideo(string $contentType): bool
    {
        return str_starts_with($contentType, 'video');
    }

    private function addGeneralParameters(array &$params, Message $message): void
    {
        $params['read_state'] = true;
        $params['random_id'] = random_int(1000000000, 9999999999);
        if ($message->id) {
            $params['guid'] = $message->id;
        }
        $payload = null;
        if ($message->trackingData !== null) {
            $params['payload'] = $payload = json_encode($message->trackingData, JSON_UNESCAPED_UNICODE);
        }
        if ($message->replyToMessageId) {
            $params['reply_to'] = $message->replyToMessageId;
        }
        if ($keyboard = $message->keyboard) {
            $this->addKeyboardParameters($params, $keyboard, $payload);
        }
    }

    private function addKeyboardParameters(array &$params, Keyboard $keyboard, ?string $payload): void
    {
        $buttons = [];
        foreach ($keyboard->buttons as $rows) {
            $row = [];
            foreach ($rows as $button) {
                if ($btn = $this->extractButtonParameters($button, $payload)) {
                    $row[] = $btn;
                }
            }
            $buttons[] = $row;
        }
        $kb = [
            'buttons' => $buttons
        ];
        if ($keyboard->inline) {
            $kb['inline'] = $keyboard->inline;
        } else {
            $kb['one_time'] = $keyboard->oneTime;
        }
        $params['keyboard'] = $kb;
    }

    private function extractButtonParameters(Button $button, ?string $payload): array
    {
        $btn = [
            'payload' => $payload
        ];
        switch ($button->actionType) {
            case ActionType::NONE:
            case ActionType::REPLY:
                $btn['type'] = 'text';
                $btn['label'] = $button->actionValue;
                break;
            case ActionType::OPEN_URL:
                $btn['type'] = 'open_link';
                $btn['link'] = $button->actionValue;
                $btn['label'] = $button->text;
                break;
            case ActionType::CALLBACK:
                $btn['type'] = 'callback';
                $btn['payload'] = $button->actionValue;
                $btn['label'] = $button->text;
                break;
            case ActionType::OPEN_APP:
                $btn['type'] = 'open_app';
                $btn['app_id'] = (int)$button->actionValue;
                $btn['label'] = $button->text;
                break;
            default:
                return [];
        }
        $params = [
            'action' => $btn
        ];
        if ($button->bgColor) {
            $params['color'] = $button->bgColor;
        }
        return $params;
    }
}