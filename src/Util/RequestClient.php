<?php

declare(strict_types=1);

namespace Paytrail\SDK\Util;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class RequestClient
{
    /**
     * @var ClientInterface|null
     */
    private $client;

    /**
     * @var RequestFactoryInterface|null
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    /**
     * @param ClientInterface|null         $client
     * @param RequestFactoryInterface|null $requestFactory
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $this->createClient($client, $requestFactory, $streamFactory);
    }

    /**
     * Perform http request.
     *
     * @param string                $method
     * @param string                $uri
     * @param array                 $queryParams
     * @param array<string, string> $headers
     * @param string                $body
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function request(
        string $method,
        string $uri,
        array $queryParams = [],
        array $headers = [],
        string $body = ''
    ): ResponseInterface {
        // Decode form request for Curl fallback
        try {
            if ($queryParams) {
                $uri .= '?' . http_build_query($queryParams);
            }
            $request = $this->requestFactory->createRequest($method, $uri);
            if ($body !== '') {
                $request = $request->withBody($this->streamFactory->createStream($body));
            }
            if ($headers) {
                foreach ($headers as $name => $header) {
                    $request = $request->withHeader($name, $header);
                }
            }
            return $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            // @todo Exception handling
            $clientException = new ClientException($exception->getMessage());
            if ($exception->hasResponse()) {
                $responseBody = $exception->getResponse()->getBody()->getContents();
                $clientException->setResponseBody($responseBody);
                $clientException->setResponseCode($exception->getCode());
            }
            throw $clientException;
        }
    }

    /**
     * Create http client. Use existing Guzzle if found one, else fallback to curl.
     *
     * @param ClientInterface|null         $client
     * @param RequestFactoryInterface|null $requestFactory
     *
     * @return void
     */
    private function createClient(
        ?ClientInterface $client,
        ?RequestFactoryInterface $requestFactory,
        ?StreamFactoryInterface $streamFactory
    ): void {
        if ($client !== null) {
            $this->client = $client;
        } else {
            if (!class_exists(Psr18ClientDiscovery::class)) {
                // @todo Throw an exception
            }
            $this->client = Psr18ClientDiscovery::find();
        }
        if ($requestFactory !== null) {
            $this->requestFactory = $requestFactory;
        } else {
            if (!class_exists(Psr17FactoryDiscovery::class)) {
                // @todo Throw an exception
            }
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }
        if ($streamFactory !== null) {
            $this->streamFactory = $streamFactory;
        } else {
            if (!class_exists(Psr17FactoryDiscovery::class)) {
                // @todo Throw an exception
            }
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }
    }
}
