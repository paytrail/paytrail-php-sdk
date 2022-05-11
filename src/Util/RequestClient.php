<?php
declare(strict_types=1);

namespace Paytrail\SDK\Util;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Exception\RequestException;

class RequestClient
{
    const DEFAULT_TIMEOUT = 10;

    private $client;

    public function __construct()
    {
        $this->createClient();
    }

    public function request(string $method, string $uri, array $options)
    {
        try {
            return $this->client->request($method, $uri, $options);
        } catch (GuzzleClientException $exception) {
            $clientException = new ClientException($exception->getMessage());
            if ($exception->hasResponse()) {
                $responseBody = $exception->getResponse()->getBody()->getContents();
                $clientException->SetResponseBody($responseBody);
                $clientException->setResponseCode($exception->getCode());
            }
            throw $clientException;
        }
        catch (GuzzleRequestException $exception) {
            throw new RequestException($exception->getMessage());
        }
    }

    private function createClient(): void
    {
        if (class_exists('GuzzleHttp\Client')) {
            $this->client = new GuzzleHttpClient(
                [
                    'headers' => [],
                    'base_uri' => Client::API_ENDPOINT,
                    'timeout' => self::DEFAULT_TIMEOUT,
                ]
            );
        }
        else {
            throw new \Exception('Client not found');
        }
    }
}
