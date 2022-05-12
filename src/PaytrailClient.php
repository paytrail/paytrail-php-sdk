<?php
declare(strict_types=1);

namespace Paytrail\SDK;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\Signature;

class PaytrailClient
{
    /**
     * Format request headers.
     *
     * @param string $method The request method. GET or POST.
     * @param string|null $transactionId Paytrail transaction ID when accessing single transaction not required for a new payment request.
     * @param string|null $checkoutTokenizationId Paytrail tokenization ID for getToken request
     *
     * @return array
     * @throws \Exception
     */
    protected function getHeaders(string $method, string $transactionId = null, string $checkoutTokenizationId = null): array
    {
        $datetime = new \DateTime();

        $headers = [
            'checkout-account' => $this->merchantId,
            'checkout-algorithm' => 'sha256',
            'checkout-method' => strtoupper($method),
            'checkout-nonce' => uniqid('', true),
            'checkout-timestamp' => $datetime->format('Y-m-d\TH:i:s.u\Z'),
            'platform-name' => $this->platformName,
            'content-type' => 'application/json; charset=utf-8',
        ];

        if (!empty($transactionId)) {
            $headers['checkout-transaction-id'] = $transactionId;
        }

        if (!empty($checkoutTokenizationId)) {
            $headers['checkout-tokenization-id'] = $checkoutTokenizationId;
        }

        return $headers;
    }

    /**
     * Validate a request item.
     *
     * @param $item
     *
     * @throws ValidationException
     */
    protected function validateRequestItem($item): void
    {
        if (method_exists($item, 'validate')) {
            try {
                $item->validate();
            } catch (\Exception $e) {
                $message = $e->getMessage();
                throw new ValidationException($message, $e->getCode(), $e);
            }
        }
    }

    /**
     * The PSR message interface defines headers as
     * an associative array where every header key has
     * an array of values. This method reduces the values to one.
     *
     * @param array[][] $headers The response headers.
     *
     * @return array
     */
    protected function reduceHeaders(array $headers = []): array
    {
        return array_map(function ($value) {
            return $value[0] ?? $value;
        }, $headers);
    }

    /**
     * A proxy for the Signature class' static method
     * to be used via a client instance.
     *
     * @param array $params The parameters.
     * @param string $body The body.
     * @return string SHA-256 HMAC
     */
    protected function calculateHmac(array $params = [], string $body = ''): string
    {
        return Signature::calculateHmac($params, $body, $this->secretKey);
    }
}