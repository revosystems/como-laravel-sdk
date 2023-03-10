<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class MeanOfPayment implements ArrayableContract
{
    use Arrayable;
    
    public function __construct(
        public string $type,
        public int $amount,
    ){}
}
