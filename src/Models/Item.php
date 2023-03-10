<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class Item implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public String $code,
        public String $action,
        public Int $quantity,
        public Int $netAmount,
        public String $lineId,
    ){}

    public static function fromArray(array $item): static
    {
        return new static(
            code: $item['code'],
            action: $item['action'],
            quantity: $item['quantity'],
            netAmount: $item['netAmount'],
            lineId: $item['lineId'],
        );
    }
}
