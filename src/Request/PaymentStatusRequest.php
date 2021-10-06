<?php

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Exception\ValidationException;

/**
 * Class PaymentStatusRequest
 *
 * @package OpMerchantServices\SDK\Request
 */
class PaymentStatusRequest
{

    /**
     * Payment transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        if (empty($this->transactionId)) {
            throw new ValidationException('Transaction id is empty');
        }

        return true;
    }

    /**
     * Get the transaction id.
     *
     * @return string
     */
    public function getTransactionId() : string
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     *
     * @return PaymentStatusRequest Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId) : PaymentStatusRequest
    {
        $this->transactionId = $transactionId;

        return $this;
    }
}
