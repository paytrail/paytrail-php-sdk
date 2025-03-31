<?php

/**
 * Class ItemTest
 */

declare(strict_types=1);

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Interfaces\CommissionInterface;
use Paytrail\SDK\Interfaces\ItemInterface;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class ItemTest
 *
 * This class defines payment item details.
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=item
 * @package Paytrail\SDK\Model
 */
class Item implements \JsonSerializable, ItemInterface
{
    use JsonSerializable;

    /**
     * Price per unit, VAT included, in each country's
     * minor unit, e.g. for Euros use cents.
     *
     * @var integer
     */
    protected $unitPrice;

    /**
     * Quantity, how many items ordered.
     *
     * @var integer
     */
    protected $units;

    /**
     * The VAT percentage.
     *
     * @var float
     */
    protected $vatPercentage;

    /**
     * Merchant product code.
     * May appear on invoices of certain payment methods.
     *
     * @var string
     */
    protected $productCode;

    /**
     * The delivery date.
     *
     * @var string
     * @deprecated
     */
    protected $deliveryDate;

    /**
     * ItemInterface description.
     * May appear on invoices of certain payment methods.
     *
     * @var string
     */
    protected $description;

    /**
     * Merchant specific item category.
     *
     * @var string
     */
    protected $category;

    /**
     * Unique identifier for this item.
     * Required for Shop-in-Shop payments.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Reference for this item.
     * Required for Shop-in-Shop payments.
     *
     * @var string
     */
    protected $reference;

    /**
     * Merchant ID for the item.
     * Required for Shop-in-Shop payments, do not use for normal payments.
     *
     * @var string|null
     */
    protected $merchant;

    /**
     * Shop-in-Shop commission.
     * Do not use for normal payments.
     *
     * @var CommissionInterface
     */
    protected $commission;

    /**
     * Get the unit price.
     *
     * @return int
     */
    public function getUnitPrice(): ?int
    {
        return $this->unitPrice;
    }

    /**
     * Set the unit price.
     *
     * @param int $unitPrice
     * @return ItemInterface Return self to enable chaining.
     */
    public function setUnitPrice(?int $unitPrice): ItemInterface
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get the units.
     *
     * @return int
     */
    public function getUnits(): ?int
    {
        return $this->units;
    }

    /**
     * Set the units.
     *
     * @param int $units
     * @return ItemInterface Return self to enable chaining.
     */
    public function setUnits(?int $units): ItemInterface
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get the VAT percentage.
     *
     * @return float
     */
    public function getVatPercentage(): ?float
    {
        return $this->vatPercentage;
    }

    /**
     * Set the VAT percentage.
     *
     * @param float $vatPercentage
     * @return ItemInterface Return self to enable chaining.
     */
    public function setVatPercentage(?float $vatPercentage): ItemInterface
    {
        $this->vatPercentage = $vatPercentage;

        return $this;
    }

    /**
     * Get the product code.
     *
     * @return string
     */
    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    /**
     * Set the product code.
     *
     * @param string $productCode
     * @return ItemInterface Return self to enable chaining.
     */
    public function setProductCode(?string $productCode): ItemInterface
    {
        $this->productCode = $productCode;

        return $this;
    }

    /**
     * Get the delivery date.
     *
     * @return string
     *
     * @deprecated Delivery date is deprecated
     */
    public function getDeliveryDate(): ?string
    {
        return $this->deliveryDate;
    }

    /**
     * Set the delivery date.
     *
     * @param string $deliveryDate
     * @return ItemInterface Return self to enable chaining.
     *
     * @deprecated Delivery date is deprecated
     */
    public function setDeliveryDate(?string $deliveryDate): ItemInterface
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description.
     *
     * @param string $description
     * @return ItemInterface Return self to enable chaining.
     */
    public function setDescription(?string $description): ItemInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the category.
     *
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * Set the category.
     *
     * @param string $category
     * @return ItemInterface Return self to enable chaining.
     */
    public function setCategory(?string $category): ItemInterface
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp(): ?string
    {
        return $this->stamp;
    }

    /**
     * Set the stamp.
     *
     * @param string $stamp
     * @return ItemInterface Return self to enable chaining.
     */
    public function setStamp(?string $stamp): ItemInterface
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * Set the reference.
     *
     * @param string $reference
     * @return ItemInterface Return self to enable chaining.
     */
    public function setReference(?string $reference): ItemInterface
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the merchant.
     *
     * @return string
     */
    public function getMerchant(): ?string
    {
        return $this->merchant;
    }

    /**
     * Set the merchant.
     *
     * @param string $merchant
     * @return ItemInterface Return self to enable chaining.
     */
    public function setMerchant(?string $merchant): ItemInterface
    {
        $this->merchant = $merchant;

        return $this;
    }

    /**
     * Get the commission.
     *
     * @return CommissionInterface
     */
    public function getCommission(): ?CommissionInterface
    {
        return $this->commission;
    }

    /**
     * Set the commission.
     *
     * @param CommissionInterface $commission
     * @return ItemInterface Return self to enable chaining.
     */
    public function setCommission(?CommissionInterface $commission): ItemInterface
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if ($props['unitPrice'] === null) {
            throw new ValidationException('Item unitPrice is empty');
        }
        if ($props['unitPrice'] > 99999999) {
            throw new ValidationException('Items unitPrice can\'t be over 99999999');
        }
        if ($props['units'] === null) {
            throw new ValidationException('Item units is empty');
        }
        if ($props['units'] < 0) {
            throw new ValidationException('Items units can\'t be a negative number');
        }
        if ($props['vatPercentage'] === null) {
            throw new ValidationException('Item vatPercentage is empty');
        }
        if ($props['vatPercentage'] < 0) {
            throw new ValidationException('Items vatPercentage can\'t be a negative number');
        }
        if (empty($props['productCode'])) {
            throw new ValidationException('productCode is empty');
        }
        if ($props['description'] !== null && mb_strlen($props['description']) > 1000) {
            throw new ValidationException('description does not meet maximum length of 1000');
        }

        return true;
    }

    /**
     * Validates shop-in-shop props and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validateShopInShop()
    {
        $props = get_object_vars($this);

        if (empty($props['merchant'])) {
            throw new ValidationException('merchant is empty');
        }

        if ($props['unitPrice'] < 0) {
            throw new ValidationException('Shop-in-shop item unitPrice can\'t be a negative number');
        }

        return true;
    }
}
