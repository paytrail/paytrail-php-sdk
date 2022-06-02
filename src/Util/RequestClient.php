<?php
declare(strict_types=1);

namespace Paytrail\SDK\Util;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Exception\RequestException;

class RequestClient
{
    private $client;

    public function __construct()
    {
        $this->createClient();
    }

    /**
     * @throws ClientException
     * @throws RequestException
     */
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
        } catch (GuzzleConnectException $exception) {
            throw new RequestException($exception->getMessage());
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
                    'timeout' => Client::DEFAULT_TIMEOUT,
                ]
            );
        }
        else {
            throw new \Exception('Guzzle client not found');
        }
    }
}
