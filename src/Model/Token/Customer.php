<?php

/**
 * Class Customer
 */

namespace Paytrail\SDK\Model\Token;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

/**
 * Class Customer
 *
 * The customer class defines the customer details object.
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=customer
 * @package Paytrail\SDK\Model\Token
 */
class Customer implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToSnake();

        if (empty($props['network_address'])) {
            throw new ValidationException('Network address is empty');
        }

        if (empty($props['country_code'])) {
            throw new ValidationException('Country code is empty');
        }

        return true;
    }

    /** @var string|null $networkAddress */
    protected $networkAddress;

    /** @var string|null $countryCode */
    protected $countryCode;

    /**
     * @param string|null $networkAddress
     * @return Customer
     */
    public function setNetworkAddress(?string $networkAddress): Customer
    {
        $this->networkAddress = $networkAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkAddress(): ?string
    {
        return $this->networkAddress;
    }

    /**
     * @param string|null $countryCode
     * @return Customer
     */
    public function setCountryCode(?string $countryCode): Customer
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     */
    public function jsonSerialize(): array
    {
        return array_filter($this->convertObjectVarsToSnake(), function ($item) {
            return $item !== null;
        });
    }

    /**
     * @param \stdClass $customer
     * @return Customer
     */
    public function loadFromStdClass(\stdClass $customer): Customer
    {
        if (isset($customer->network_address)) {
            $this->setNetworkAddress($customer->network_address);
        }

        if (isset($customer->country_code)) {
            $this->setCountryCode($customer->country_code);
        }

        return $this;
    }
}
