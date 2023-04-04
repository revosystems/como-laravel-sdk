<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class Payment implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public string $paymentMethod,
        public int $amount,
    ){}

    public static function fromArray(array $array): static
    {
        return new static(
            paymentMethod: $array['paymentMethod'],
            amount: abs($array['amount']),
        );
    }
}
