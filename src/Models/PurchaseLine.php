<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class PurchaseLine implements ArrayableContract
{
    use Arrayable;
    
    public function __construct(
        public string $lineId,
        public string $code,
        public string $name,
        public string $departmentCode,
        public string $departmentName,
        public int $quantity,
        public int $grossAmount,
        public int $netAmount,
    ){}
}
