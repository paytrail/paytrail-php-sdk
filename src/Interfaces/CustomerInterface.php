<?php
/**
 * Interface Customer
 */

namespace OpMerchantServices\SDK\Interfaces;

use OpMerchantServices\SDK\Exception\ValidationException;

/**
 * Interface Customer
 *
 * An interface for all Customer classes to implement.
 *
 * @package OpMerchantServices\SDK
 */
interface CustomerInterface
{
    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): ?string;

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return self Return self to enable chaining.
     */
    public function setEmail(?string $email) : CustomerInterface;

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): ?string;

    /**
     * Set first name.
     *
     * @param string $firstName
     *
     * @return self Return self to enable chaining.
     */
    public function setFirstName(?string $firstName) : CustomerInterface;

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName(): ?string;

    /**
     * Set last name.
     *
     * @param string $lastName
     *
     * @return self Return self to enable chaining.
     */
    public function setLastName(?string $lastName) : CustomerInterface;

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): ?string;

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return self Return self to enable chaining.
     */
    public function setPhone(?string $phone) : CustomerInterface;

    /**
     * Get VAT id.
     *
     * @return string
     */
    public function getVatId(): ?string;

    /**
     * Set VAT id.
     *
     * @param string $vatId
     *
     * @return self Return self to enable chaining.
     */
    public function setVatId(?string $vatId) : CustomerInterface;
}