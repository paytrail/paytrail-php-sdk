<?php
declare(strict_types=1);

/**
 * Class RefundResponse
 */

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class RefundResponse
 *
 * @package Paytrail\SDK\Response
 */
class InvoiceActivationResponse implements ResponseInterface
{

    /**
     * Request status, ok / error.
     *
     * @var string
     */
    protected $status;

    /**
     * Get the status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status.
     *
     * @param string $status
     *
     * @return InvoiceActivationResponse Return self to enable chaining.
     */
    public function setStatus(string $status): InvoiceActivationResponse
    {
        $this->status = $status;
        return $this;
    }
}
