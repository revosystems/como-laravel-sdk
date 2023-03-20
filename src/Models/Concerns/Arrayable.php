<?php

namespace Revo\ComoSdk\Models\Concerns;

use Illuminate\Support\Collection;
use Throwable;

trait Arrayable
{
    public function toArray(): array
    {
        return collect(get_object_vars($this))
            ->map(function($property) {
                try {
                    return $property->toArray();
                } catch (Throwable) {
                    return $property;
                }
            })
            // Filter out properties with null value or empty string, array or collection
            ->filter(fn($property) => $property != null || $property === 0)
            ->all();
    }   
    
    public static function manyFromArray(array $assets): Collection
    {
        return collect($assets)->map(fn (array $asset) => static::fromArray($asset));
    } 
}
