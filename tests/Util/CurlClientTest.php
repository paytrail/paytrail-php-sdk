<?php

declare(strict_types=1);

namespace Tests\Util;

use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Exception\RequestException;
use Paytrail\SDK\Response\CurlResponse;
use Paytrail\SDK\Util\CurlClient;
use PHPUnit\Framework\TestCase;

class CurlClientTest extends TestCase
{
    private const HEADERS = "HTTP/1.1 200 OK\nsignature: abc123";

    private const ERROR_BODY = '{"status":"error","message":"Failed to create token payment.",'
        . '"acquirerResponseCode":"116","acquirerResponseCodeDescription":"Insufficient funds"}';

    public static function successStatusProvider(): array
    {
        return [
            '200 OK' => [200],
            '201 Created' => [201],
            '302 Found' => [302],
        ];
    }

    public static function clientErrorStatusProvider(): array
    {
        return [
            '400 Bad Request' => [400],
            '401 Unauthorized' => [401],
            '403 Forbidden' => [403],
            '404 Not Found' => [404],
            '422 Unprocessable Entity' => [422],
            '499 upper bound' => [499],
        ];
    }

    public static function serverErrorStatusProvider(): array
    {
        return [
            '500 Internal Server Error' => [500],
            '502 Bad Gateway' => [502],
            '503 Service Unavailable' => [503],
        ];
    }

    /**
     * @dataProvider successStatusProvider
     */
    public function testSuccessStatusReturnsResponse(int $statusCode): void
    {
        $response = $this->createResponse(self::HEADERS, '{"transactionId":"123"}', $statusCode);

        $this->assertInstanceOf(CurlResponse::class, $response);
        $this->assertSame($statusCode, $response->getStatusCode());
        $this->assertSame('{"transactionId":"123"}', $response->getBody());
        $this->assertSame(['abc123'], $response->getHeader('signature'));
    }

    /**
     * @dataProvider clientErrorStatusProvider
     */
    public function testClientErrorStatusThrowsClientException(int $statusCode): void
    {
        try {
            $this->createResponse(self::HEADERS, self::ERROR_BODY, $statusCode);
            $this->fail('Expected a ClientException for status ' . $statusCode);
        } catch (ClientException $exception) {
            $this->assertSame(self::ERROR_BODY, $exception->getResponseBody());
            $this->assertSame($statusCode, $exception->getResponseCode());
            $this->assertSame(self::ERROR_BODY, $exception->getMessage());
            $this->assertSame($statusCode, $exception->getCode());
        }
    }

    /**
     * @dataProvider serverErrorStatusProvider
     */
    public function testServerErrorStatusThrowsRequestException(int $statusCode): void
    {
        try {
            $this->createResponse(self::HEADERS, 'Bad Gateway', $statusCode);
            $this->fail('Expected a RequestException for status ' . $statusCode);
        } catch (RequestException $exception) {
            $this->assertSame('Bad Gateway', $exception->getMessage());
            $this->assertSame($statusCode, $exception->getCode());
        }
    }

    /**
     * Call the protected CurlClient::createResponse() without making a request.
     */
    private function createResponse(string $headers, string $body, int $statusCode): CurlResponse
    {
        $client = new class ('https://example.invalid', 1) extends CurlClient {
            public function exposedCreateResponse(string $headers, string $body, int $statusCode): CurlResponse
            {
                return $this->createResponse($headers, $body, $statusCode);
            }
        };

        return $client->exposedCreateResponse($headers, $body, $statusCode);
    }
}
