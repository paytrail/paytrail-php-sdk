<?php
/**
 * Interface PaymentRequest
 */

namespace OpMerchantServices\SDK\Interfaces;

use OpMerchantServices\SDK\Exception\ValidationException;

/**
 * Interface PaymentRequest
 *
 * An interface for all payment request classes to implement.
 *
 * @package OpMerchantServices\SDK
 */
interface PaymentRequestInterface
{
    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp() : ?string;

    /**
     * Set the stamp.
     *
     * @param string $stamp
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setStamp(?string $stamp): PaymentRequestInterface;

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference() : ?string;

    /**
     * Set the reference.
     *
     * @param string $reference
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setReference(?string $reference): PaymentRequestInterface;

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : ?int;

    /**
     * Set the amount.
     *
     * @param int $amount
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setAmount(?int $amount) : PaymentRequestInterface;

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency() : ?string;

    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCurrency(?string $currency) : PaymentRequestInterface;

    /**
     * Get the language.
     *
     * @return string
     */
    public function getLanguage() : ?string;

    /**
     * Set the language.
     *
     * @param string $language
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setLanguage(?string $language) : PaymentRequestInterface;

    /**
     * Get the items.
     *
     * @return ItemInterface[]
     */
    public function getItems() : ?array;

    /**
     * Set the items.
     *
     * @param ItemInterface[] $items
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setItems(?array $items) : PaymentRequestInterface;

    /**
     * Get the customer.
     *
     * @return CustomerInterface
     */
    public function getCustomer() : ?CustomerInterface;

    /**
     * Set the customer.
     *
     * @param CustomerInterface $customer
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCustomer(?CustomerInterface $customer) : PaymentRequestInterface;

    /**
     * Get the delivery address.
     *
     * @return AddressInterface
     */
    public function getDeliveryAddress() : ?AddressInterface;

    /**
     * Set the delivery address.
     *
     * @param AddressInterface $deliveryAddress
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setDeliveryAddress(?AddressInterface $deliveryAddress) : PaymentRequestInterface;

    /**
     * Get the invoicing address.
     *
     * @return AddressInterface
     */
    public function getInvoicingAddress() : ?AddressInterface;

    /**
     * Set the invoicing address.
     *
     * @param AddressInterface $invoicingAddress
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setInvoicingAddress(?AddressInterface $invoicingAddress) : PaymentRequestInterface;

    /**
     * Get the redirect urls.
     *
     * @return CallbackUrlInterface
     */
    public function getRedirectUrls() : ?CallbackUrlInterface;

    /**
     * Set the redirect urls.
     *
     * @param CallbackUrlInterface $redirectUrls
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setRedirectUrls(?CallbackUrlInterface $redirectUrls) : PaymentRequestInterface;

    /**
     * Get callback urls.
     *
     * @return CallbackUrlInterface
     */
    public function getCallbackUrls() : ?CallbackUrlInterface;

    /**
     * Set callback urls.
     *
     * @param CallbackUrlInterface $callbackUrls
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCallbackUrls(?CallbackUrlInterface $callbackUrls) : PaymentRequestInterface;

    /**
     * @return int
     */
    public function getCallbackDelay(): int;

    /**
     * @param int $callbackDelay
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCallbackDelay(int $callbackDelay) : PaymentRequestInterface;

    /**
     * @param string[] $groups
     * @return PaymentRequestInterface
     */
    public function setGroups(array $groups) : PaymentRequestInterface;

    /**
     * @return string[]
     */
    public function getGroups() : array;
}
