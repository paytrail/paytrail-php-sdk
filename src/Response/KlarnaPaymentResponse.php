<?php

/**
 * Class KlarnaPaymentResponse
 */

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class KlarnaPaymentResponse
 *
 * Response of the Klarna charge, authorization hold and commit endpoints.
 * stepUpUrl is set when the customer must be redirected to finish the payment (HTTP 403).
 *
 * @package Paytrail\SDK\Response
 */
class KlarnaPaymentResponse implements ResponseInterface
{
    /**
     * The transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * URL to redirect the customer to when step-up is required.
     *
     * @var string
     */
    protected $stepUpUrl;

    /**
     * Error message returned with a step-up, e.g. "Step-up required".
     *
     * @var string
     */
    protected $error;

    /**
     * @param string|null $transactionId
     * @return KlarnaPaymentResponse Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId): KlarnaPaymentResponse
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @param string|null $stepUpUrl
     * @return KlarnaPaymentResponse Return self to enable chaining.
     */
    public function setStepUpUrl(?string $stepUpUrl): KlarnaPaymentResponse
    {
        $this->stepUpUrl = $stepUpUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStepUpUrl(): ?string
    {
        return $this->stepUpUrl;
    }

    /**
     * @param string|null $error
     * @return KlarnaPaymentResponse Return self to enable chaining.
     */
    public function setError(?string $error): KlarnaPaymentResponse
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
