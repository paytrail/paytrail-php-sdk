<?php

namespace Paytrail\SDK\Util;

use Paytrail\SDK\Response\CurlResponse;

class CurlClient
{
    private $baseUrl;
    private $timeout;

    public function __construct(string $baseUrl, int $timeout)
    {
        $this->baseUrl = $baseUrl;
        $this->timeout = $timeout;
    }

    /**
     * Perform http request
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return CurlResponse
     * @throws \Paytrail\SDK\Exception\ClientException
     */
    public function request(string $method, string $uri, array $options)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->buildUrl($uri, $options));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parseHeaders($options));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        if ($method == 'POST') {
            $body = $this->formatBody($options['body']);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = rtrim(substr($response, 0, $header_size));
        $body = substr($response, $header_size);

        $curlResponse = new CurlResponse($headers, $body, $statusCode);

        curl_close($curl);

        return $curlResponse;
    }

    /**
     * Build URL by prefixing endpoint with base URL and possible query parameters.
     *
     * @param string $uri
     * @param array $options
     * @return string
     */
    private function buildUrl(string $uri, array $options): string
    {
        $query = '';
        if (isset($options['query'])) {
            $uri .= '?' . http_build_query($options['query']);
        }

        return $this->baseUrl . $uri . $query;
    }

    /**
     * Parse Key => Value headers as Key: Value array for curl.
     * @param $options
     * @return array
     */
    private function parseHeaders($options): array
    {
        $headers = $options['headers'] ?? [];
        $result = [];
        foreach ($headers as $key => $value) {
            $result[] = $key .': ' . $value;
        }

        return $result;
    }

    /**
     * Format body. For form request, build normal query string.
     *
     * @param $body
     * @return string
     */
    private function formatBody($body): string
    {
        if (!is_array($body)) {
            return $body;
        }
        return http_build_query($body,'','&');
    }
}