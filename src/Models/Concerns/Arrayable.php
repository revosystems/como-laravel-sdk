<?php

namespace Revo\ComoSdk\Models\Concerns;

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
            ->filter()
            ->all();
    }    
}
