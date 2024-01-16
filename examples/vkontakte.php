<?php

require_once __DIR__.'/../vendor/autoload.php';

$vkontakte = new \Twin\Messenger\VKontakteMessenger(new \Twin\Messenger\Client\VKontakteClient());
$vkontakte->authenticate(new \Twin\Messenger\Auth\Credentials(getenv('VKONTAKTE_SECRET_TOKEN')));

$userMessage = $vkontakte->receiveMessage(json_decode(file_get_contents('php://input'), true));

$textMessage = new Twin\Messenger\BotMessage\TextMessage();
$textMessage->body = sprintf('Hi, %s!', $userMessage->user->firstName ?? $userMessage->user->id);

$textMessage->replyToMessageId = $userMessage->id;
$vkontakte->sendMessage($userMessage->user->id, $textMessage);

return 'ok';
