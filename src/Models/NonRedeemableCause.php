<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class NonRedeemableCause implements ArrayableContract
{
    use Arrayable;
    
    public function __construct(
        public string $code,
        public string $message,
    ){}

    public static function fromArray(?array $cause): ?static
    {
        if(! $cause) {
            return null;
        }

        return new static(
            code: $cause['code'],
            message: $cause['message'],
        );
    }
}
