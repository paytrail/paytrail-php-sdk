<?php
/**
 * Interface TokenPaymentRequest
 */

namespace OpMerchantServices\SDK\Interfaces;

/**
 * Interface TokenPaymentRequest
 *
 * An interface for TokenPaymentRequest to implement.
 *
 * @package OpMerchantServices\SDK
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