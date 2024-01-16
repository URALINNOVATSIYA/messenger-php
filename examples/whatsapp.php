<?php

require_once __DIR__.'/../vendor/autoload.php';

$whatsapp = new \Twin\Messenger\WhatsappMessenger(new \Twin\Messenger\Client\WhatsappClient());
$whatsapp->authenticate(new \Twin\Messenger\Auth\Credentials(getenv('WHATSAPP_SECRET_TOKEN'), getenv('WHATSAPP_ACCOUNT_ID')));

$userMessage = $whatsapp->receiveMessage(json_decode(file_get_contents('php://input'), true));

$textMessage = new Twin\Messenger\BotMessage\TextMessage();
$textMessage->body = sprintf('Hi, %s!', $userMessage->user->firstName);

$textMessage->replyToMessageId = $userMessage->id;
$whatsapp->sendMessage($userMessage->user->id, $textMessage);

http_response_code(200);
