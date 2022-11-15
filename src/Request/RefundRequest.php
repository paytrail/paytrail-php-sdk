<?php

/**
 * Class Refund
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\RefundItem;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class Refund
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=http-request-body
 *
 * @package Paytrail\SDK\Request
 */
class RefundRequest implements \JsonSerializable
{
    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if (! empty($this->items)) {
            // Count the total amount of the payment.
            $items_total = array_reduce($this->items, function ($carry = 0, ?RefundItem $item = null) {
                if ($item === null) {
                    return $carry;
                }
                return $item->getAmount() + $carry;
            });

            // Validate items.
            array_walk($this->items, function (RefundItem $item) {
                $item->validate();
            });
        } else {
            // If no items are set, fallback to prevent validation errors.
            $items_total = $this->amount;
        }

        if (empty($props['amount'])) {
            throw new ValidationException('Amount can not be empty');
        }

        if (filter_var($props['amount'], FILTER_VALIDATE_INT) === false) {
            throw new ValidationException('Amount is not an integer');
        }

        if ($items_total !== $props['amount']) {
            throw new ValidationException('ItemsTotal does not match Amount');
        }

        if (empty($props['callbackUrls'])) {
            throw new ValidationException('CallbackUrls are not set');
        }

        $this->callbackUrls->validate();

        return true;
    }

    /**
     * Total amount to refund, in currency's minor units.
     *
     * @var int
     */
    protected $amount;

    /**
     * Array of items to refund. Use only for Shop-in-Shop payments.
     *
     * @var RefundItem[]
     */
    protected $items;

    /**
     * Which urls to ping after the refund has been processed.
     *
     * A single callbackurl object holding the success and cancellation urls.
     *
     * @var CallbackUrl
     */
    protected $callbackUrls;

    /**
     * Refund recipient email address.
     *
     * @var $mail
     */
    protected $email;

    /**
     * Merchant unique identifier for the refund.
     *
     * @var $refundStamp
     */
    protected $refundStamp;

    /**
     * Refund reference.
     *
     * @var $refundReference
     */
    protected $refundReference;

    /**
     * Get the amount.
     *
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * Set the amount.
     *
     * @param int|null $amount
     *
     * @return RefundRequest Return self to enable chaining.
     */
    public function setAmount(?int $amount): RefundRequest
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get the items.
     *
     * @return RefundItem[]
     */
    public function getItems(): array
    {
        return $this->items ?? [];
    }

    /**
     * Set the items.
     *
     * @param RefundItem[] $items The
     *
     * @return RefundRequest Return self to enable chaining.
     */
    public function setItems(?array $items): RefundRequest
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Get the callback urls.
     *
     * @return CallbackUrl|null
     */
    public function getCallbackUrls(): ?CallbackUrl
    {
        return $this->callbackUrls;
    }

    /**
     * Set the callback urls.
     *
     * @param CallbackUrl|null $callbackUrls The callback url instance holding success and cancel urls.
     *
     * @return RefundRequest Return self to enable chaining.
     */
    public function setCallbackUrls(?CallbackUrl $callbackUrls): RefundRequest
    {
        $this->callbackUrls = $callbackUrls;
        return $this;
    }

    /**
     * Get customer email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set customer email
     *
     * @param string|null $email
     * @return RefundRequest
     */
    public function setEmail(?string $email): RefundRequest
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get refund stamp.
     *
     * @return string|null
     */
    public function getRefundStamp(): ?string
    {
        return $this->refundStamp;
    }

    /**
     * Set refund stamp.
     *
     * @param string|null $refundStamp
     * @return RefundRequest
     */
    public function setRefundStamp(?string $refundStamp): RefundRequest
    {
        $this->refundStamp = $refundStamp;
        return $this;
    }

    /**
     * Get refund reference.
     *
     * @return string|null
     */
    public function getRefundReference(): ?string
    {
        return $this->refundReference;
    }

    /**
     * Set refund reference.
     *
     * @param string|null $refundReference
     * @return RefundRequest
     */
    public function setRefundReference(?string $refundReference): RefundRequest
    {
        $this->refundReference = $refundReference;
        return $this;
    }
}
