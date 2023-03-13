<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class ExtendedData implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public Item $item,
        public Int $discount,
        public Int $discountedQuantity,
        public Collection $discountAllocation,
    ){}

    public static function fromArray(array $data): static
    {
        return new static(
            item: Item::fromArray($data['item']),
            discount: $data['discount'],
            discountedQuantity: $data['discountedQuantity'],
            discountAllocation: DiscountAllocation::manyFromArray($data['discountAllocation']),
        );
    }
}
