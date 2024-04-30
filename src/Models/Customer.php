<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class Customer implements ArrayableContract
{
    use Arrayable;
    
    public function __construct(
        public ?string $phoneNumber = null,
        public ?string $email = null,
        public ?string $appClientId = null,
        public ?string $customIdentifier = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $temporaryToken = null,
        public ?string $commonExtId = null,
        public ?bool $allowEmail = null,
        public ?bool $allowSMS = null,
        public ?bool $termsOfUse = null,
    ){}

    public function registerData(): array
    {
        return array_filter(
            $this->toArray(),
            fn ($key) => in_array($key, ['phoneNumber', 'email', 'firstName', 'lastName', 'allowEmail', 'allowSMS', 'termsOfUse']),
            ARRAY_FILTER_USE_KEY
        );
    }
}
