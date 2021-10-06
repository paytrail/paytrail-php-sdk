<?php
/**
 * Class GetTokenRequest
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

/**
 * Class GetTokenRequest
 *
 * @package Paytrail\SDK\Request
 */
class GetTokenRequest implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /** @var string $paytrailTokenizationId */
    protected $paytrailTokenizationId;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToDashed();

        if (empty($props['paytrail-tokenization-id'])) {
            throw new ValidationException('Paytrail tokenization id is empty');
        }

        return true;
    }

    public function setpaytrailTokenizationId(string $paytrailTokenizationId): GetTokenRequest
    {
        $this->paytrailTokenizationId = $paytrailTokenizationId;

        return $this;
    }

    /**
     * @return string
     */
    public function getpaytrailTokenizationId(): string
    {
        return $this->paytrailTokenizationId;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter($this->convertObjectVarsToDashed(), function ($item) {
            return $item !== null;
        });
    }
}