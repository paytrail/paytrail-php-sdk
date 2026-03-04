<?php

/**
 * Class InvoiceCancellationResponse
 */

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class InvoiceCancellationResponse
 *
 * Possible HTTP status codes returned by Paytrail API:
 *   201 - Invoice cancelled
 *   202 - Invoice cancellation requested, status of the payment will be updated asynchronously
 *   200 - Invoice already cancelled
 *   400 - Invalid request. Refer to body message for more information
 *   500 - Other error. Refer to body message for more information
 *
 * @package Paytrail\SDK\Response
 */
class InvoiceCancellationResponse implements ResponseInterface
{
    protected $status;
    protected $httpStatusCode;
    protected $message;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): InvoiceCancellationResponse
    {
        $this->status = $status;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): InvoiceCancellationResponse
    {
        $this->message = $message;
        return $this;
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }

    public function setHttpStatusCode(?int $httpStatusCode): InvoiceCancellationResponse
    {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }
}
