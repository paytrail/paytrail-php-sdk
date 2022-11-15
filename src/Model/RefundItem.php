<?php

/**
 * Class RefundItem
 */

declare(strict_types=1);

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class RefundItem
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=refunditem
 *
 * @package Paytrail\SDK\Model
 */
class RefundItem implements \JsonSerializable
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

        if (empty($props['amount'])) {
            throw new ValidationException('Amount is empty');
        }

        if (empty($props['stamp'])) {
            throw new ValidationException('Stamp can not be empty');
        }

        if (!is_string($props['stamp'])) {
            throw new ValidationException('Stamp is not a string');
        }

        return true;
    }

    /**
     * Total amount to refund this item, in currency's minor units.
     *
     * @var int
     */
    protected $amount;

    /**
     * Unique stamp of the refund item.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Set the total amount to refund this item, in currency's minor units.
     *
     * @param int $amount The amount.
     * @return RefundItem Return self to enable chaining.
     */
    public function setAmount(?int $amount): RefundItem
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp(): string
    {
        return $this->stamp;
    }

    /**
     * Set a unique stamp of the refund item.
     *
     * @param string $stamp The stamp.
     * @return RefundItem Return self to enable chaining.
     */
    public function setStamp(?string $stamp): RefundItem
    {
        $this->stamp = $stamp;

        return $this;
    }
}
