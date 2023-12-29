<?php

namespace Revo\ComoSdk\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Revo\ComoSdk\Models\Concerns\Arrayable;
use Revo\ComoSdk\Models\Enums\OrderType;

class Purchase implements ArrayableContract
{
    use Arrayable;

    public string $openTime;
    public string $orderType;

    public function __construct(
        Carbon $openTime,
        public int $totalAmount,
        OrderType $orderType,
        public ?Collection $items = null,
        public ?string $employee = null,
        public ?int $totalGeneralDiscount = null,
        public ?string $transactionId = null,
        public ?string $relatedTransaction = null,
        public ?Collection $meansOfPayment = null,
        public ?array $tags = null,
    ){
        $this->transactionId ??= Uuid::uuid4()->toString();
        $this->orderType = $orderType->value;
        $this->openTime = $openTime->format('Y-m-d\TH:i:s\Z');
    }
}
