<?php
/**
 * Trait JsonSerializable
 */

namespace OpMerchantServices\SDK\Util;

/**
 * Trait JsonSerializable
 */
trait JsonSerializable
{

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($item) {
            return $item !== null;
        });
    }
}
