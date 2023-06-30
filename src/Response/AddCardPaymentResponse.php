<?php

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class PaymentResponse
 *
 * Represents a response object of payment creation.
 *
 * @package Paytrail\SDK\Response
 */
class AddCardPaymentResponse implements ResponseInterface
{
    /**
     * Assigned transaction ID for the payment.
     *
     * @var string|null
     */
    protected $transactionId;

    /**
     * URL to hosted payment gateway.
     *
     * @var string|null
     */
    protected $redirectUrl;

    /**
     * Get the transaction id.
     *
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string|null $transactionId
     *
     * @return self Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId): AddCardPaymentResponse
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get the redirectUrl.
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * Set the redirectUrl.
     *
     * @param string|null $redirectUrl
     *
     * @return self Return self to enable chaining.
     */
    public function setRedirectUrl(?string $redirectUrl): AddCardPaymentResponse
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }
}
