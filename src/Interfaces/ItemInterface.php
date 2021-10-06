<?php
/**
 * Interface Item
 */

namespace OpMerchantServices\SDK\Interfaces;

use OpMerchantServices\SDK\Exception\ValidationException;

/**
 * Interface Item
 *
 * An interface for all Item classes to implement.
 *
 * @package OpMerchantServices\SDK
 */
interface ItemInterface
{
    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * Get the unit price.
     *
     * @return int
     */
    public function getUnitPrice(): ?int;

    /**
     * Set the unit price.
     *
     * @param int $unitPrice
     * @return ItemInterface Return self to enable chaining.
     */
    public function setUnitPrice(?int $unitPrice) : ItemInterface;

    /**
     * Get the units.
     *
     * @return int
     */
    public function getUnits(): ?int;

    /**
     * Set the units.
     *
     * @param int $units
     * @return ItemInterface Return self to enable chaining.
     */
    public function setUnits(?int $units) : ItemInterface;

    /**
     * Get the VAT percentage.
     *
     * @return int
     */
    public function getVatPercentage(): ?int;

    /**
     * Set the VAT percentage.
     *
     * @param int $vatPercentage
     * @return ItemInterface Return self to enable chaining.
     */
    public function setVatPercentage(?int $vatPercentage) : ItemInterface;

    /**
     * Get the product code.
     *
     * @return string
     */
    public function getProductCode(): ?string;

    /**
     * Set the product code.
     *
     * @param string $productCode
     * @return ItemInterface Return self to enable chaining.
     */
    public function setProductCode(?string $productCode) : ItemInterface;

    /**
     * Get the delivery date.
     *
     * @return string
     */
    public function getDeliveryDate(): ?string;

    /**
     * Set the delivery date.
     *
     * @param string $deliveryDate
     * @return ItemInterface Return self to enable chaining.
     */
    public function setDeliveryDate(?string $deliveryDate) : ItemInterface;

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * Set the description.
     *
     * @param string $description
     * @return ItemInterface Return self to enable chaining.
     */
    public function setDescription(?string $description) : ItemInterface;

    /**
     * Get the category.
     *
     * @return string
     */
    public function getCategory(): ?string;

    /**
     * Set the category.
     *
     * @param string $category
     * @return ItemInterface Return self to enable chaining.
     */
    public function setCategory(?string $category) : ItemInterface;

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp(): ?string;

    /**
     * Set the stamp.
     *
     * @param string $stamp
     * @return ItemInterface Return self to enable chaining.
     */
    public function setStamp(?string $stamp) : ItemInterface;

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference(): ?string;

    /**
     * Set the reference.
     *
     * @param string $reference
     * @return ItemInterface Return self to enable chaining.
     */
    public function setReference(?string $reference) : ItemInterface;

    /**
     * Get the merchant.
     *
     * @return string
     */
    public function getMerchant(): ?string;

    /**
     * Set the merchant.
     *
     * @param string $merchant
     * @return ItemInterface Return self to enable chaining.
     */
    public function setMerchant(?string $merchant) : ItemInterface;

    /**
     * Get the commission.
     *
     * @return ComissionInterface
     */
    public function getCommission(): ?ComissionInterface;

    /**
     * Set the commission.
     *
     * @param ComissionInterface $commission
     * @return ItemInterface Return self to enable chaining.
     */
    public function setCommission(?ComissionInterface $commission) : ItemInterface;
}
