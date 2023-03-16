<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class RedeemAsset implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public string $name,
        public bool $redeemable,
        public Collection $benefits,
        public ?NonRedeemableCause $nonRedeemableCause = null,
        public ?string $code = null,
        public ?string $key = null,
    ){}

    public static function fromArray(array $redeemAsset): static
    {
        return new static(
            name: $redeemAsset['name'],
            redeemable: $redeemAsset['redeemable'],
            benefits: Benefit::manyFromArray($redeemAsset['benefits'] ?? []),
            nonRedeemableCause: NonRedeemableCause::fromArray($redeemAsset['nonRedeemableCause'] ?? []),
            key: $redeemAsset['key'] ?? null,
            code: $redeemAsset['code'] ?? null,
        );
    }
}
