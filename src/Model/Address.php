<?php
/**
 * Class AddressInterface
 */

namespace OpMerchantServices\SDK\Model;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Interfaces\AddressInterface;
use OpMerchantServices\SDK\Util\JsonSerializable;

/**
 * Class AddressInterface
 *
 * This class defines address details for a payment request.
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=address
 * @package OpMerchantServices\SDK\Model
 */
class Address implements \JsonSerializable, AddressInterface
{

    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['streetAddress'])) {
            throw new ValidationException('streetAddress is empty');
        }

        if (empty($props['postalCode'])) {
            throw new ValidationException('postalCode is empty');
        }

        if (empty($props['city'])) {
            throw new ValidationException('city is empty');
        }

        if (empty($props['country'])) {
            throw new ValidationException('country is empty');
        }

        return true;
    }

    /**
     * The street address.
     *
     * @var string
     */
    protected $streetAddress;

    /**
     * The postal code.
     *
     * @var string
     */
    protected $postalCode;

    /**
     * The city.
     *
     * @var string
     */
    protected $city;

    /**
     * The county.
     *
     * @var string
     */
    protected $county;

    /**
     * The country.
     *
     * @var string
     */
    protected $country;

    /**
     * Get the street address.
     *
     * @return string
     */
    public function getStreetAddress(): ?string
    {

        return $this->streetAddress;
    }

    /**
     * Set the sttreet address.
     *
     * @param string $streetAddress
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setStreetAddress(?string $streetAddress) : AddressInterface
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    /**
     * Get the postal code.
     *
     * @return string
     */
    public function getPostalCode(): ?string
    {

        return $this->postalCode;
    }

    /**
     * Set the tostal code.
     *
     * @param string $postalCode
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setPostalCode(?string $postalCode) : AddressInterface
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get the city.
     *
     * @return string
     */
    public function getCity(): ?string
    {

        return $this->city;
    }

    /**
     * Set the city.
     *
     * @param string $city
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setCity(?string $city) : AddressInterface
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the county.
     *
     * @return string
     */
    public function getCounty(): ?string
    {

        return $this->county;
    }

    /**
     * Set the county.
     *
     * @param string $county
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setCounty(?string $county) : AddressInterface
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get the country.
     *
     * @return string
     */
    public function getCountry(): ?string
    {

        return $this->country;
    }

    /**
     * Set the country.
     *
     * @param string $country
     *
     * @return AddressInterface Return self to enable chaining.
     */
    public function setCountry(?string $country) : AddressInterface
    {
        $this->country = $country;

        return $this;
    }
}
