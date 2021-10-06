<?php
/**
 * Interface CallbackUrl
 */

namespace OpMerchantServices\SDK\Interfaces;

use OpMerchantServices\SDK\Exception\ValidationException;

/**
 * Interface CallbackUrl
 *
 * An interface for all CallbackUrl classes to implement.
 *
 * @package OpMerchantServices\SDK
 */
interface CallbackUrlInterface
{
    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * Get the success url.
     *
     * @return string
     */
    public function getSuccess(): ?string;

    /**
     * Set the success url.
     *
     * @param string $success
     * @return CallbackUrlInterface Return self to enable chaining.
     */
    public function setSuccess(?string $success): CallbackUrlInterface;

    /**
     * Get the cancellation url.
     *
     * @return string
     */
    public function getCancel(): ?string;

    /**
     * Set the cancellation url.
     *
     * @param string $cancel
     * @return CallbackUrlInterface Return self to enable chaining.
     */
    public function setCancel(?string $cancel): CallbackUrlInterface;
}
