<?php

namespace Revo\ComoSdk\Models;

class Monetary
{
    public function __construct(
        public int $monetary,
        public int $nonMonetary,
    ){}

    public static function fromArray(array $monetary): static
    {
        return new static(
            monetary: $monetary['monetary'],
            nonMonetary: $monetary['nonMonetary'],
        );
    }
}
