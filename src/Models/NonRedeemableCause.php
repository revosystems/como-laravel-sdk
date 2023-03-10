<?php

namespace Revo\ComoSdk\Models;

class NonRedeemableCause
{
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
