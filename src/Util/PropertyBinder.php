<?php
/**
 * Trait PropertyBinder
 */

namespace OpMerchantServices\SDK\Util;

/**
 * Trait PropertyBinder
 *
 * This trait enables binding class properties
 * with an array or object of key-value pairs.
 */
trait PropertyBinder
{

    /**
     * Binds the passed properties to a class instance.
     *
     * @param \stdClass|array $props The properties.
     * @return self Return self to enable chaining.
     */
    public function bindProperties($props)
    {
        if (! empty($props)) {
            if (is_object($props)) {
                $props = get_object_vars($props);
            }
            array_walk($props, function ($value, $key) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            });
        }

        return $this;
    }
}
