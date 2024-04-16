<?php

declare(strict_types=1);

namespace Paytrail\SDK;

use JsonSerializable;
use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Exception\HmacException;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\RequestClient;
use Paytrail\SDK\Util\Signature;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

abstract class PaytrailClient
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

    /**
     * @param ClientInterface|null         $client
     * @param RequestFactoryInterface|null $requestFactory
     *
     * @return void
     */
    protected function createHttpClient(
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null
    ): void {
        $this->http_client = new RequestClient($client, $requestFactory);
    }

    /**
     * A proxy for the Signature class' static method
     * to be used via a client instance.
     *
     * @param array $response The response parameters.
     * @param string $body The response body.
     * @param string $signature The response signature key.
     *
     * @throws HmacException
     */
    abstract public function validateHmac(array $response = [], string $body = '', string $signature = '');

    /**
     * A wrapper for post requests.
     *
     * @param string                $uri The uri for the request.
     * @param JsonSerializable|null $data The request payload.
     * @param callable|null         $callback The callback method to run for the decoded response.
     *                                         If left empty, the response is returned.
     * @param string|null           $transactionId Paytrail transaction ID when accessing single transaction.
     *                                             Not required for a new payment request.
     * @param bool                  $signatureInHeader Checks if signature is calculated from header/body parameters
     * @param string|null           $paytrailTokenizationId Paytrail tokenization ID for getToken request
     *
     * @return mixed
     * @throws HmacException
     * @throws ClientException
     */
    protected function post(
        string $uri,
        JsonSerializable $data = null,
        callable $callback = null,
        string $transactionId = null,
        bool $signatureInHeader = true,
        string $paytrailTokenizationId = null
    ) {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);

        if ($signatureInHeader) {
            $headers = $this->getHeaders('POST', $transactionId, $paytrailTokenizationId);
            $mac = $this->calculateHmac($headers, $body);
            $headers['signature'] = $mac;

            $response = $this->http_client->request(
                'POST',
                $uri,
                [
                    'allow_redirects' => false
                ],
                $headers,
                $body
            );

            $body = (string)$response->getBody();

            // Handle header data and validate HMAC.
            $headers = $this->reduceHeaders($response->getHeaders());
            $this->validateHmac($headers, $body, $headers['signature'] ?? '');
        } else {
            // @phpstan-ignore-next-line FIXME
            $mac = $this->calculateHmac($data->toArray());
            // @phpstan-ignore-next-line FIXME
            $data->setSignature($mac);
            // @phpstan-ignore-next-line FIXME
            $body = json_encode($data->toArray(), JSON_UNESCAPED_SLASHES);

            $response = $this->http_client->request(
                'POST',
                $uri,
                [
                    'allow_redirects' => false
                ],
                [],
                $body
            );

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
     * @param string        $uri           The uri for the request.
     * @param callable|null $callback      The callback method to run for the decoded response.
     *                                     If left empty, the response is returned.
     * @param string|null   $transactionId Paytrail transaction ID when accessing single transaction.
     *                                     Not required for a new payment request.
     *
     * @return mixed
     * @throws ClientException
     * @throws HmacException
     */
    protected function get(string $uri, callable $callback = null, string $transactionId = null)
    {
        $headers = $this->getHeaders('GET', $transactionId);
        $mac = $this->calculateHmac($headers);

        $headers['signature'] = $mac;

        $response = $this->http_client->request(
            'GET',
            $uri,
            [],
            $headers
        );

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
     * @param string|null $transactionId Paytrail transaction ID when accessing single transaction.
     * Not required for a new payment request.
     * @param string|null $checkoutTokenizationId Paytrail tokenization ID for getToken request
     *
     * @return array
     */
    protected function getHeaders(
        string $method,
        string $transactionId = null,
        string $checkoutTokenizationId = null
    ): array {
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
