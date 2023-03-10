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
    ){}
}
