<?php

require_once __DIR__.'/../vendor/autoload.php';

$viber = new \Twin\Messenger\ViberMessenger(new \Twin\Messenger\Client\ViberClient());
$viber->authenticate(new \Twin\Messenger\Auth\Credentials(getenv('VIBER_SECRET_TOKEN')));

$userMessage = $viber->receiveMessage(json_decode(file_get_contents('php://input'), true));

$textMessage = new Twin\Messenger\BotMessage\TextMessage();
$textMessage->body = sprintf('Hi, %s!', $userMessage->user->firstName);

$textMessage->replyToMessageId = $userMessage->id;
$viber->sendMessage($userMessage->user->id, $textMessage);

http_response_code(200);
