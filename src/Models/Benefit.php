<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Concerns\Arrayable;
use Revo\ComoSdk\Models\Enums\BenefitType;

class Benefit implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public BenefitType $type,
        public Collection $extendedData,
        public ?int $sum = null,
        public ?string $code = null,
    ){}

    public static function fromArray(array $benefit): static
    {
        return new static(
            type: BenefitType::from($benefit['type']),
            sum: $benefit['sum'] ?? null,
            code: $benefit['code'] ?? null,
            extendedData: ExtendedData::manyFromArray($benefit['extendedData'] ?? []),
        );
    }
}
