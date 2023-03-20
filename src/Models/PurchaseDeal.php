<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class PurchaseDeal implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public string $key,
        public int $appliedAmount,
    ){}
}
