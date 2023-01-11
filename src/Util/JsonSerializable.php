<?php

/**
 * Trait JsonSerializable
 */

declare(strict_types=1);

namespace Paytrail\SDK\Util;

trait JsonSerializable
{
    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), function ($item) {
            return $item !== null;
        });
    }
}
