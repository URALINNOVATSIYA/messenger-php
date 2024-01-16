<?php

require_once __DIR__.'/../vendor/autoload.php';

$telegram = new \Twin\Messenger\TelegramMessenger(new \Twin\Messenger\Client\TelegramClient());
$telegram->authenticate(new \Twin\Messenger\Auth\Credentials(getenv('TELEGRAM_SECRET_TOKEN')));

$userMessage = $telegram->receiveMessage(json_decode(file_get_contents('php://input'), true));

$textMessage = new Twin\Messenger\BotMessage\TextMessage();
$textMessage->body = sprintf('Hi, %s!', $userMessage->user->firstName);

$textMessage->replyToMessageId = $userMessage->id;
$telegram->sendMessage($userMessage->user->id, $textMessage);

http_response_code(200);
