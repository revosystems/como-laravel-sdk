<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class Deal implements ArrayableContract
{
    use Arrayable;
    
    public function __construct(
        public String $key,
        public String $name,
        public Collection $benefits,
    ){}

    public static function fromArray(array $deal): static
    {
        return new static(
            key: $deal['key'],
            name: $deal['name'],
            benefits: Benefit::manyFromArray($deal['benefits'] ?? []),
        );
    }
}
