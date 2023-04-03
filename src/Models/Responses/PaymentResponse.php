<?php

namespace Revo\ComoSdk\Models\Responses;

use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Enums\PaymentType;
use Revo\ComoSdk\Models\Monetary;
use Revo\ComoSdk\Models\Payment;

class PaymentResponse
{
    public Collection $payments;
    public PaymentType $type;
    public Monetary $updatedBalance;

    public function __construct(
        array $payments,
        public string $confirmation,
        string $type,
        array $updatedBalance,
    ){
        $this->payments = Payment::manyFromArray($payments);
        $this->type = PaymentType::tryFrom($type);
        $this->updatedBalance = Monetary::fromArray($updatedBalance);
    }

    public function paidAmount(): int
    {
        return $this->payments->sum->amount;
    }
}
