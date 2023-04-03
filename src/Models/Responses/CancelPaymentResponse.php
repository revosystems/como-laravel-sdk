<?php

namespace Revo\ComoSdk\Models\Responses;

use Revo\ComoSdk\Models\Enums\PaymentType;
use Revo\ComoSdk\Models\Monetary;

class CancelPaymentResponse
{
    public PaymentType $type;
    public Monetary $balance;

    public function __construct(
        string $type,
        array $balance,
    ){
        $this->type = PaymentType::tryFrom($type);
        $this->balance = Monetary::fromArray($balance);
    }
}
