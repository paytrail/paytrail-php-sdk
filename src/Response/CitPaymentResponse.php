<?php
declare(strict_types=1);

/**
 * Class CitPaymentResponse
 */

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class CitPaymentResponse
 *
 * Represents a response object of CIT payment creation.
 *
 * @package Paytrail\SDK\Response
 */
class CitPaymentResponse implements ResponseInterface
{
    /**
     * The transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /** @var string $threeDSecureUrl */
    protected $threeDSecureUrl;

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     *
     * @return CitPaymentResponse Return self to enable chaining.
     */
    public function setTransactionId(string $transactionId): CitPaymentResponse
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

    /**
     * @param string|null $threeDSecureUrl
     * @return CitPaymentResponse
     */
    public function setThreeDSecureUrl(?string $threeDSecureUrl): CitPaymentResponse
    {
        $this->threeDSecureUrl = $threeDSecureUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getThreeDSecureUrl(): ?string
    {
        return $this->threeDSecureUrl;
    }
}
