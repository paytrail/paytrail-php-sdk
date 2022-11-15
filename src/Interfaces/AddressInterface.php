<?php

/**
 * Interface Addresss
 */

declare(strict_types=1);

namespace Paytrail\SDK\Interfaces;

use Paytrail\SDK\Exception\ValidationException;

/**
 * Interface Address
 *
 * An interface for all Address classes to implement.
 *
 * @package Paytrail\SDK
 */
interface AddressInterface
{
    /**
     * Validates with Respect\Validation library and throws exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * Get the street address.
     *
     * @return string
     */
    public function getStreetAddress(): ?string;

    /**
     * Set the sttreet address.
     *
     * @param string $streetAddress
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setStreetAddress(?string $streetAddress): AddressInterface;

    /**
     * Get the postal code.
     *
     * @return string
     */
    public function getPostalCode(): ?string;

    /**
     * Set the tostal code.
     *
     * @param string $postalCode
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setPostalCode(?string $postalCode): AddressInterface;

    /**
     * Get the city.
     *
     * @return string
     */
    public function getCity(): ?string;

    /**
     * Set the city.
     *
     * @param string $city
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setCity(?string $city): AddressInterface;

    /**
     * Get the county.
     *
     * @return string
     */
    public function getCounty(): ?string;

    /**
     * Set the county.
     *
     * @param string $county
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setCounty(?string $county): AddressInterface;

    /**
     * Get the country.
     *
     * @return string
     */
    public function getCountry(): ?string;

    /**
     * Set the country.
     *
     * @param string $country
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setCountry(?string $country): AddressInterface;
}
