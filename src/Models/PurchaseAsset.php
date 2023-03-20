<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class PurchaseAsset implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public ?string $key,
        public ?string $code,
        public int $appliedAmount,
    ){}
}
