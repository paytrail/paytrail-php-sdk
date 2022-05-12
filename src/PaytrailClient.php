<?php
declare(strict_types=1);

namespace Paytrail\SDK;

use Paytrail\SDK\Exception\HmacException;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\RequestClient;
use Paytrail\SDK\Util\Signature;

class PaytrailClient
{
    /**
     * The merchant id.
     *
     * @var int
     */
    protected $merchantId;

    /**
     * The merchant secret key.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Platform name for the API.
     *
     * @var string
     */
    protected $platformName;

    /**
     * HTTP client.
     *
     * @var RequestClient
     */
    protected $http_client;

    protected function createHttpClient()
    {
        $this->http_client = new RequestClient();
    }

    /**
     * A wrapper for post requests.
     *
     * @param string $uri The uri for the request.
     * @param \JsonSerializable $data The request payload.
     * @param callable|null $callback The callback method to run for the decoded response. If left empty, the response is returned.
     * @param string|null $transactionId Paytrail transaction ID when accessing single transaction not required for a new payment request.
     * @param bool $signatureInHeader Checks if signature is calculated from header/body parameters
     * @param string|null $paytrailTokenizationId Paytrail tokenization ID for getToken request
     *
     * @return mixed
     * @throws HmacException
     */
    protected function post(string $uri, \JsonSerializable $data, callable $callback = null, string $transactionId = null, bool $signatureInHeader = true, string $paytrailTokenizationId = null)
    {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);

        if ($signatureInHeader) {
            $headers = $this->getHeaders('POST', $transactionId, $paytrailTokenizationId);
            $mac = $this->calculateHmac($headers, $body);
            $headers['signature'] = $mac;

            $response = $this->http_client->request('POST', $uri, [
                'headers' => $headers,
                'body' => $body,
                'allow_redirects' => false
            ]);

            $body = (string)$response->getBody();

            // Handle header data and validate HMAC.
            $headers = $this->reduceHeaders($response->getHeaders());
            $this->validateHmac($headers, $body, $headers['signature'] ?? '');
        } else {
            $mac = $this->calculateHmac($data->toArray());
            $data->setSignature($mac);
            $body = json_encode($data->toArray(), JSON_UNESCAPED_SLASHES);

            $response = $this->http_client->request('POST', $uri, [
                'body' => $body,
                'allow_redirects' => false
            ]);

            $body = (string)$response->getBody();
        }

        if ($callback) {
            $decoded = json_decode($body);
            return call_user_func($callback, $decoded);
        }

        return $response;
    }

    /**
     * A wrapper for get requests.
     *
     * @param string $uri The uri for the request.
     * @param callable|null $callback The callback method to run for the decoded response. If left empty, the response is returned.
     * @param string|null $transactionId Paytrail transaction ID when accessing single transaction not required for a new payment request.
     *
     * @return mixed
     * @throws HmacException
     */
    protected function get(string $uri, callable $callback = null, string $transactionId = null)
    {
        $headers = $this->getHeaders('GET', $transactionId);
        $mac = $this->calculateHmac($headers);

        $headers['signature'] = $mac;

        $response = $this->http_client->request('GET', $uri, [
            'headers' => $headers
        ]);

        $body = (string)$response->getBody();

        // Handle header data and validate HMAC.
        $responseHeaders = $this->reduceHeaders($response->getHeaders());
        $this->validateHmac($responseHeaders, $body, $responseHeaders['signature'] ?? '');

        if ($callback) {
            $decoded = json_decode($body);
            return call_user_func($callback, $decoded);
        }

        return $response;
    }

    /**
     * Format request headers.
     *
     * @param string $method The request method. GET or POST.
     * @param string|null $transactionId Paytrail transaction ID when accessing single transaction not required for a new payment request.
     * @param string|null $checkoutTokenizationId Paytrail tokenization ID for getToken request
     *
     * @return array
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