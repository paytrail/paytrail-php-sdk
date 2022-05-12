<?php
declare(strict_types=1);

/**
 * Class MitPaymentResponse
 */

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class MitPaymentResponse
 *
 * Represents a response object of MIT payment creation.
 *
 * @package Paytrail\SDK\Response
 */
class MitPaymentResponse implements ResponseInterface
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
     * @param string $transactionId
     *
     * @return MitPaymentResponse Return self to enable chaining.
     */
    public function setTransactionId(string $transactionId): MitPaymentResponse
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
