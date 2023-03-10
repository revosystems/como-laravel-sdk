<?php

namespace Revo\ComoSdk\Models;

class Balance
{
    public function __construct(
        public bool $usedByPayment,
        public Monetary $balance,
    ){}

    public static function fromArray(array $balance): ?static
    {
        return new static(
            usedByPayment: $balance['usedByPayment'],
            balance: Monetary::fromArray($balance['balance']),
        );
    }
}
