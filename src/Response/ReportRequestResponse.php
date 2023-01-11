<?php

/**
 * Class ReportRequestResponse
 */

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class ReportRequestResponse
 *
 * Represents a response object of payments report request.
 *
 * @package Paytrail\SDK\Response
 */
class ReportRequestResponse implements ResponseInterface
{
    /**
     * The request id.
     *
     * @var string
     */
    protected $requestId;

    /**
     * Set the request id.
     *
     * @param string|null $requestId
     *
     * @return ReportRequestResponse Return self to enable chaining.
     */
    public function setRequestId(?string $requestId): ReportRequestResponse
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * Get the request id.
     *
     * @return string
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }
}
