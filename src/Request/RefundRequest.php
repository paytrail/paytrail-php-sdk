<?php
/**
 * Class Refund
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\RefundItem;
use OpMerchantServices\SDK\Util\JsonSerializable;

/**
 * Class Refund
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=http-request-body
 *
 * @package OpMerchantServices\SDK\Request
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
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : int
    {
        return $this->amount;
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     *
     * @return RefundRequest Return self to enable chaining.
     */
    public function setAmount(?int $amount) : RefundRequest
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return RefundItem[]
     */
    public function getItems() : array
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
    public function setItems(?array $items) : RefundRequest
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the callback urls.
     *
     * @return CallbackUrl
     */
    public function getCallbackUrls() : CallbackUrl
    {
        return $this->callbackUrls;
    }

    /**
     * Set the callback urls.
     *
     * @param CallbackUrl $callbackUrls The callback url instance holding success and cancel urls.
     *
     * @return RefundRequest Return self to enable chaining.
     */
    public function setCallbackUrls(?CallbackUrl $callbackUrls) : RefundRequest
    {
        $this->callbackUrls = $callbackUrls;

        return $this;
    }
}
