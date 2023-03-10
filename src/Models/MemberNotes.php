<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Support\Collection;

class MemberNotes
{
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

    public static function manyFromArray(array $memberNotes): Collection
    {
        return collect($memberNotes)->map(fn (array $memberNote) => static::fromArray($memberNote));
    }
}
