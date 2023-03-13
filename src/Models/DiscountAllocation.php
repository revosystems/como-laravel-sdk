<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class DiscountAllocation implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public Int $quantity,
        public Int $unitDiscount,
    ){}

    public static function fromArray(array $allocation): static
    {
        return new static(
            quantity: $allocation['quantity'],
            unitDiscount: $allocation['unitDiscount'],
        );
    }
}
