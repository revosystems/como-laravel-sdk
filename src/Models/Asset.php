<?php

namespace Revo\ComoSdk\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Revo\ComoSdk\Models\Concerns\Arrayable;
use Revo\ComoSdk\Models\Enums\AssetStatus;

class Asset implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public string $key,
        public string $name,
        public AssetStatus $status,
        public bool $redeemable,
        public ?string $description = null,
        public ?string $image = null,
        public ?Carbon $validFrom = null,
        public ?Carbon $validUntil = null,
        public ?NonRedeemableCause $nonRedeemableCause = null,
    ){}

    public static function fromArray(array $asset): static
    {
        return new static(
            key: $asset['key'],
            name: $asset['name'],
            status: AssetStatus::from($asset['status']),
            redeemable: $asset['redeemable'],
            description: $asset['description'],
            image: $asset['image'],
            validFrom: isset($asset['validFrom']) ? Carbon::parse($asset['validFrom']) : null,
            validUntil: isset($asset['validUntil']) ? Carbon::parse($asset['validUntil']) : null,
            nonRedeemableCause: NonRedeemableCause::fromArray($asset['nonRedeemableCause'] ?? null),
        );
    }
}
