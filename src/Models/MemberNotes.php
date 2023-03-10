<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class MemberNotes implements ArrayableContract
{
    use Arrayable;

    public function __construct(
        public string $content,
        public string $type,
    ){}

    public static function fromArray(array $memberNote): static
    {
        return new static(
            content: $memberNote['content'],
            type: $memberNote['type'],
        );
    }
}
