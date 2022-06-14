<?php

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Exception\ClientException;

class CurlResponse
{
    private $body;
    private $headers;
    private $statusCode;

    public function __construct(string $headers, string $body, int $statusCode)
    {
        $this->body = $body;
        $this->setHeaders($headers);
        $this->statusCode = $statusCode;
        if ($statusCode == 404) {
            throw new ClientException($body);
        }
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set response headers
     *
     * @param string $headers
     * @return void
     */
    private function setHeaders(string $headers): void
    {
        $headerArray = explode("\n", $headers);
        $responseHeaders = [];

        foreach ($headerArray as $header) {
            if (!strpos($header, ':')) {
                continue;
            }
            [$key, $value] = explode(': ', $header);
            $responseHeaders[$key] = [rtrim($value)];
        }
        $this->headers = $responseHeaders;
    }
}