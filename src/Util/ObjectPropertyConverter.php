<?php
/**
 * Trait ObjectPropertyConverter
 */

namespace OpMerchantServices\SDK\Util;

/**
 * Trait ObjectPropertyConverter
 *
 * Functions for converting object vars
 */
trait ObjectPropertyConverter
{
    /**
     * @param string $string
     * @return string
     */
    public function convertCamelToDashed(string $string): string
    {
        return strtolower(preg_replace('/([A-Z])/', '-$1', $string));
    }

    /**
     * @param string $string
     * @return string
     */
    public function convertCamelToSnake(string $string): string
    {
        return strtolower(preg_replace('/([A-Z])/', '_$1', $string));
    }

    /**
     * @return array
     */
    public function convertObjectVarsToDashed(): array
    {
        $props = get_object_vars($this);
        $propsConverted = [];

        foreach ($props as $key => $prop) {
            $propsConverted[$this->convertCamelToDashed($key)] = $prop;
        }

        return $propsConverted;
    }

    /**
     * @return array
     */
    public function convertObjectVarsToSnake(): array
    {
        $props = get_object_vars($this);
        $propsConverted = [];

        foreach ($props as $key => $prop) {
            $propsConverted[$this->convertCamelToSnake($key)] = $prop;
        }

        return $propsConverted;
    }
}
