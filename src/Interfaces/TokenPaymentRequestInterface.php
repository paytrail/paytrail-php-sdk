<?php

/**
 * Interface TokenPaymentRequest
 */

declare(strict_types=1);

namespace Paytrail\SDK\Interfaces;

/**
 * Interface TokenPaymentRequest
 *
 * An interface for TokenPaymentRequest to implement.
 *
 * @package Paytrail\SDK
 */
interface TokenPaymentRequestInterface extends PaymentRequestInterface
{
    /**
     * @param string $token
     * @return TokenPaymentRequestInterface
     */
    public function setToken(string $token): TokenPaymentRequestInterface;

    /**
     * @return string
     */
    public function getToken(): string;
}
