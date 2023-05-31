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
    ){}

    public function registerData(): array
    {
        return array_filter($this->toArray(), fn ($key) => in_array($key, ['phoneNumber', 'email', 'firstName']), ARRAY_FILTER_USE_KEY);
    }
}
