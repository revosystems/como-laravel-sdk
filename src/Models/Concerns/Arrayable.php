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
            ->filter(fn($property) => $property != null)
            ->all();
    }   
    
    public static function manyFromArray(array $assets): Collection
    {
        return collect($assets)->map(fn (array $asset) => static::fromArray($asset));
    } 
}
