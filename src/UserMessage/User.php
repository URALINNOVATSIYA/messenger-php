<?php

namespace Twin\Messenger\UserMessage;

class User
{
    public string $id;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $phone;
    public ?string $email;
    public UserLanguage $language;

    public function __construct(
        string $id,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phone = null,
        ?string $email = null,
        UserLanguage $language = UserLanguage::ENG,
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
        $this->language = $language;
    }
}
