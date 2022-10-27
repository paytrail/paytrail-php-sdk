<?php
declare(strict_types=1);

/**
 * Class RevertCitPaymentAuthHoldResponse
 */

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class RevertCitPaymentAuthHoldResponse
 *
 * Represents a response object of CIT payment authorization hold revert.
 *
 * @package Paytrail\SDK\Response
 */
class RevertPaymentAuthHoldResponse implements ResponseInterface
{
    /**
     * The transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Set the transaction id.
     *
     * @param string|null $transactionId
     *
     * @return RevertPaymentAuthHoldResponse Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId): RevertPaymentAuthHoldResponse
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * Get the transaction id.
     *
     * @return string
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }
}
