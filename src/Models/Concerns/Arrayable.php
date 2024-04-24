<?php

namespace Revo\ComoSdk\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Throwable;
use BackedEnum;

trait Arrayable
{
    public function toArray(): array
    {
        return collect(get_object_vars($this))
            ->map(function($property) {
                try {
                    return match (true) {
                        $property instanceof Carbon => $property->toIso8601ZuluString(),
                        $property instanceof BackedEnum => $property->value,
                        default => $property->toArray(),
                    };
                } catch (Throwable) {
                    return $property;
                }
            })
            // Filter out properties with null value or empty string, array or collection
            ->filter(fn($property) => $property != null || is_bool($property) || $property === 0)
            ->all();
    }   
    
    public static function manyFromArray(array $assets): Collection
    {
        return collect($assets)->map(fn (array $asset) => static::fromArray($asset));
    } 
}
