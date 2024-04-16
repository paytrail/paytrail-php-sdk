<?php

/**
 * Class Client
 */

declare(strict_types=1);

namespace Paytrail\SDK;

use Paytrail\SDK\Model\Provider;
use Paytrail\SDK\Request\AddCardFormRequest;
use Paytrail\SDK\Request\CitPaymentRequest;
use Paytrail\SDK\Request\GetTokenRequest;
use Paytrail\SDK\Request\MitPaymentRequest;
use Paytrail\SDK\Request\PaymentRequest;
use Paytrail\SDK\Request\ReportBySettlementRequest;
use Paytrail\SDK\Request\ReportRequest;
use Paytrail\SDK\Request\SettlementRequest;
use Paytrail\SDK\Request\ShopInShopPaymentRequest;
use Paytrail\SDK\Request\PaymentStatusRequest;
use Paytrail\SDK\Request\RefundRequest;
use Paytrail\SDK\Request\EmailRefundRequest;
use Paytrail\SDK\Request\RevertPaymentAuthHoldRequest;
use Paytrail\SDK\Response\CitPaymentResponse;
use Paytrail\SDK\Response\GetTokenResponse;
use Paytrail\SDK\Response\MitPaymentResponse;
use Paytrail\SDK\Response\PaymentResponse;
use Paytrail\SDK\Response\PaymentStatusResponse;
use Paytrail\SDK\Response\RefundResponse;
use Paytrail\SDK\Response\EmailRefundResponse;
use Paytrail\SDK\Response\ReportRequestResponse;
use Paytrail\SDK\Response\RevertPaymentAuthHoldResponse;
use Paytrail\SDK\Response\SettlementResponse;
use Paytrail\SDK\Response\InvoiceActivationResponse;
use Paytrail\SDK\Util\Signature;
use Paytrail\SDK\Exception\HmacException;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Exception\RequestException;
use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Response\AddCardPaymentResponse;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * Class Client
 *
 * The client is the connector class for the API.
 *
 * @package Paytrail\SDK
 */
class Client extends PaytrailClient
{
    /**
     * The Paytrail API endpoint.
     */
    public const API_ENDPOINT = 'https://services.paytrail.com';

    /**
     * Http client timeout seconds
     */
    public const DEFAULT_TIMEOUT = 10;

    /**
     * Client constructor.
     *
     * @param int $merchantId The merchant.
     * @param string $secretKey The secret key.
     * @param string $platformName Platform name.
     * @param ClientInterface|null $client HTTP client
     * @param RequestFactoryInterface|null $requestFactory HTTP request factory
     */
    public function __construct(
        int $merchantId,
        string $secretKey,
        string $platformName,
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null
    ) {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->platformName = $platformName;

        $this->createHttpClient($client, $requestFactory);
    }

    /**
     * Get a list of payment providers.
     *
     * @param int|null $amount Purchase amount in currency's minor unit.
     * @return Provider[]
     *
     * @throws HmacException       Thrown if HMAC calculation fails for responses.
     * @throws ClientException
     * @throws RequestException    A Guzzle HTTP request exception is thrown for erroneous requests.
     */
    public function getPaymentProviders(int $amount = null): array
    {
        $uri = '/merchants/payment-providers';

        $headers = $this->getHeaders('GET');
        $mac = $this->calculateHmac($headers);

        // Sign the request.
        $headers['signature'] = $mac;
        $queryParams = [];

        // Set the amount query parameter.
        if ($amount !== null) {
            $queryParams = [
                'amount' => $amount
            ];
        }

        $response = $this->http_client->request(
            'GET',
            $uri,
            $queryParams,
            $headers
        );
        $body = (string)$response->getBody();

        // Validate the signature.
        $headers = $this->reduceHeaders($response->getHeaders());
        $this->validateHmac($headers, $body, $headers['signature'] ?? '');

        // Instantiate providers.
        $decoded = json_decode($body);
        $providers = array_map(function ($provider_data) {
            return (new Provider())->bindProperties($provider_data);
        }, $decoded);

        return $providers;
    }

    /**
     * Returns an array of following grouped payment providers fields:
     * terms: Localized text with a link to the terms of payment.
     * groups: Array of payment method group data (id, name, icon, svg, providers)
     *
     * @param int|null $amount Purchase amount in currency's minor unit.
     * @param string $locale
     * @param array $groups
     * @return array
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ClientException
     * @throws RequestException A Guzzle HTTP request exception is thrown for erroneous requests.
     */
    public function getGroupedPaymentProviders(int $amount = null, string $locale = 'FI', array $groups = []): array
    {
        $uri = '/merchants/grouped-payment-providers';

        $headers = $this->getHeaders('GET');
        $mac = $this->calculateHmac($headers);
        $res = [];

        // Sign the request.
        $headers['signature'] = $mac;
        $queryParams = [
            'language' => $locale
        ];

        // Set the amount query parameter.
        if (null !== $amount) {
            $queryParams['amount'] = $amount;
        }

        if (!empty($groups)) {
            $queryParams['groups'] = join(',', $groups);
        }

        $response = $this->http_client->request(
            'GET',
            $uri,
            $queryParams,
            $headers
        );
        $body = (string)$response->getBody();

        // Validate the signature.
        $headers = $this->reduceHeaders($response->getHeaders());
        $this->validateHmac($headers, $body, $headers['signature'] ?? '');

        // Instantiate providers.
        $decoded = json_decode($body);

        $groups = array_map(function ($group_data) {
            return [
                'id' => $group_data->id,
                'name' => $group_data->name,
                'icon' => $group_data->icon,
                'svg' => $group_data->svg,
                'providers' => array_map(function ($provider_data) {
                    return (new Provider())->bindProperties($provider_data);
                }, $group_data->providers),
            ];
        }, $decoded->groups);

        $res['terms'] = $decoded->terms;
        $res['groups'] = $groups;

        return $res;
    }

    /**
     * Create a payment request.
     *
     * @param PaymentRequest $payment A payment class instance.
     *
     * @return PaymentResponse
     * @throws HmacException        Thrown if HMAC calculation fails for responses.
     * @throws ValidationException  Thrown if payment validation fails.
     */
    public function createPayment(PaymentRequest $payment): PaymentResponse
    {
        $this->validateRequestItem($payment);

        $uri = '/payments';

        $payment_response = $this->post(
            $uri,
            $payment,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return PaymentResponse
             */
            function ($decoded) {
                return (new PaymentResponse())
                    ->setTransactionId($decoded->transactionId ?? null)
                    ->setHref($decoded->href ?? null)
                    ->setTerms($decoded->terms ?? null)
                    ->setGroups($decoded->groups ?? [])
                    ->setReference($decoded->reference ?? null)
                    ->setProviders($decoded->providers ?? [])
                    ->setCustomProviders((array)($decoded->customProviders ?? []));
            }
        );

        return $payment_response;
    }

    /**
      * Create a shop-in-shop payment request.
      *
      * @param ShopInShopPaymentRequest $payment A payment class instance.
      *
      * @return PaymentResponse
      * @throws HmacException        Thrown if HMAC calculation fails for responses.
      * @throws ValidationException  Thrown if payment validation fails.
      */
    public function createShopInShopPayment(ShopInShopPaymentRequest $payment): PaymentResponse
    {
        $this->validateRequestItem($payment);

        $uri = '/payments';

        $paymentResponse = $this->post(
            $uri,
            $payment,
            /**
            * Create the response instance.
            *
            * @param mixed $decoded The decoded body.
            * @return PaymentResponse
            */
            function ($decoded) {
                return (new PaymentResponse())
                ->setTransactionId($decoded->transactionId ?? null)
                ->setHref($decoded->href ?? null)
                ->setTerms($decoded->terms ?? null)
                ->setGroups($decoded->groups ?? [])
                ->setReference($decoded->reference ?? null)
                ->setProviders($decoded->providers ?? []);
            }
        );
        return $paymentResponse;
    }

    public function createPaymentAndAddCard(PaymentRequest $paymentRequest): AddCardPaymentResponse
    {
        $this->validateRequestItem($paymentRequest);

        $uri = '/tokenization/pay-and-add-card';

        return $this->post(
            $uri,
            $paymentRequest,
            function ($decoded) {
                return (new AddCardPaymentResponse())
                    ->setTransactionId($decoded->transactionId ?? null)
                    ->setRedirectUrl($decoded->redirectUrl ?? null);
            }
        );
    }

    /**
     * Create a payment status request.
     *
     * @param PaymentStatusRequest $paymentStatusRequest Payment status request
     *
     * @return PaymentStatusResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function getPaymentStatus(PaymentStatusRequest $paymentStatusRequest): PaymentStatusResponse
    {
        $this->validateRequestItem($paymentStatusRequest);

        $uri = '/payments/' . $paymentStatusRequest->getTransactionId();

        $payment_status_response = $this->get(
            $uri,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return PaymentStatusResponse
             */
            function ($decoded) {
                return (new PaymentStatusResponse())
                    ->setTransactionId($decoded->transactionId)
                    ->setStatus($decoded->status ?? null)
                    ->setAmount($decoded->amount ?? null)
                    ->setCurrency($decoded->currency ?? null)
                    ->setStamp($decoded->stamp ?? null)
                    ->setReference($decoded->reference ?? null)
                    ->setCreatedAt($decoded->createdAt ?? null)
                    ->setHref($decoded->href ?? null)
                    ->setProvider($decoded->provider ?? null)
                    ->setFilingCode($decoded->filingCode ?? null)
                    ->setPaidAt($decoded->paidAt ?? null);
            },
            $paymentStatusRequest->getTransactionId()
        );

        return $payment_status_response;
    }

    /**
     * Refunds a payment by transaction ID.
     *
     * @see https://paytrail.github.io/api-documentation/#/?id=refund
     *
     * @param RefundRequest $refund A refund instance.
     * @param string $transactionID The transaction id.
     *
     * @return RefundResponse Returns a refund response after successful refunds.
     * @throws HmacException        Thrown if HMAC calculation fails for responses.
     * @throws ValidationException  Thrown if payment validation fails.
     */
    public function refund(RefundRequest $refund, string $transactionID = ''): RefundResponse
    {
        $this->validateRequestItem($refund);

        try {
            $uri = '/payments/' . $transactionID . '/refund';

            // This will throw an error if the refund is not created.
            $refund_response = $this->post(
                $uri,
                $refund,
                /**
                 * Create the response instance.
                 *
                 * @param mixed $decoded The decoded body.
                 * @return RefundResponse
                 */
                function ($decoded) {
                    return (new RefundResponse())
                        ->setProvider($decoded->provider ?? null)
                        ->setStatus($decoded->status ?? null)
                        ->setTransactionId($decoded->transactionId ?? null);
                },
                $transactionID
            );
        } catch (HmacException $e) {
            throw $e;
        }

        return $refund_response;
    }

    /**
     * Refunds a payment by transaction ID as an email refund.
     *
     * @see https://paytrail.github.io/api-documentation/#/?id=email-refund
     *
     * @param EmailRefundRequest $refund An email refund instance.
     * @param string $transactionID The transaction id.
     *
     * @return EmailRefundResponse Returns a refund response after successful refunds.
     * @throws HmacException       Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function emailRefund(EmailRefundRequest $refund, string $transactionID = ''): EmailRefundResponse
    {
        $this->validateRequestItem($refund);

        try {
            $uri = '/payments/' . $transactionID . '/refund/email';

            // This will throw an error if the refund is not created.
            $refund_response = $this->post(
                $uri,
                $refund,
                /**
                 * Create the response instance.
                 *
                 * @param mixed $decoded The decoded body.
                 * @return EmailRefundResponse
                 */
                function ($decoded) {
                    return (new EmailRefundResponse())
                        ->setProvider($decoded->provider ?? null)
                        ->setStatus($decoded->status ?? null)
                        ->setTransactionId($decoded->transactionId ?? null);
                },
                $transactionID
            );
        } catch (HmacException $e) {
            throw $e;
        }

        return $refund_response;
    }

    /**
     * Create a AddCardForm request.
     *
     * @param AddCardFormRequest $addCardFormRequest A AddCardFormRequest class instance.
     *
     * @return mixed
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if AddCardFormRequest validation fails.
     */
    public function createAddCardFormRequest(AddCardFormRequest $addCardFormRequest)
    {
        $this->validateRequestItem($addCardFormRequest);

        $uri = '/tokenization/addcard-form';

        $addCardFormResponse = $this->post(
            $uri,
            $addCardFormRequest,
            null,
            null,
            false
        );

        return $addCardFormResponse;
    }

    /**
     * @param GetTokenRequest $getTokenRequest A GetTokenRequest class instance.
     * @return GetTokenResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function createGetTokenRequest(GetTokenRequest $getTokenRequest): GetTokenResponse
    {
        $this->validateRequestItem($getTokenRequest);
        $paytrailTokenizationId = $getTokenRequest->getCheckoutTokenizationId();

        try {
            $uri = '/tokenization/' . $getTokenRequest->getCheckoutTokenizationId();

            $getTokenResponse = $this->post(
                $uri,
                $getTokenRequest,
                /**
                 * Create the response instance.
                 *
                 * @param mixed $decoded The decoded body.
                 * @return GetTokenResponse
                 */
                function ($decoded) {
                    return (new GetTokenResponse())
                        ->loadFromStdClass($decoded);
                },
                null,
                true,
                $paytrailTokenizationId
            );
        } catch (HmacException $e) {
            throw $e;
        }

        return $getTokenResponse;
    }

    /**
     * @param CitPaymentRequest $citPayment
     * @return CitPaymentResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     * @throws RequestException
     */
    public function createCitPaymentCharge(CitPaymentRequest $citPayment): CitPaymentResponse
    {
        $uri = '/payments/token/cit/charge';

        return $this->createCitPayment($citPayment, $uri);
    }

    /**
     * @param CitPaymentRequest $citPayment
     * @return CitPaymentResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     * @throws RequestException
     */
    public function createCitPaymentAuthorizationHold(CitPaymentRequest $citPayment): CitPaymentResponse
    {
        $uri = '/payments/token/cit/authorization-hold';

        return $this->createCitPayment($citPayment, $uri);
    }

    /**
     * @param MitPaymentRequest $mitPayment
     * @return MitPaymentResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException  Thrown if payment validation fails.
     */
    public function createMitPaymentCharge(MitPaymentRequest $mitPayment): MitPaymentResponse
    {
        $uri = '/payments/token/mit/charge';

        return $this->createMitPayment($mitPayment, $uri);
    }

    /**
     * @param MitPaymentRequest $mitPayment
     * @return MitPaymentResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function createMitPaymentAuthorizationHold(MitPaymentRequest $mitPayment): MitPaymentResponse
    {
        $uri = '/payments/token/mit/authorization-hold';

        return $this->createMitPayment($mitPayment, $uri);
    }

    /**
     * @param CitPaymentRequest $citPayment
     * @param string $transactionId The transaction id.
     *
     * @return CitPaymentResponse
     *
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function createCitPaymentCommit(
        CitPaymentRequest $citPayment,
        string $transactionId = ''
    ): CitPaymentResponse {
        $this->validateRequestItem($citPayment);

        $uri = '/payments/' . $transactionId . '/token/commit';

        $citPaymentResponse = $this->post(
            $uri,
            $citPayment,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return CitPaymentResponse
             */
            function ($decoded) {
                return (new CitPaymentResponse())
                    ->setTransactionId($decoded->transactionId ?? null)
                    ->setThreeDSecureUrl($decoded->threeDSecureUrl ?? null);
            },
            $transactionId
        );

        return $citPaymentResponse;
    }

    /**
     * @param MitPaymentRequest $mitPayment
     * @param string $transactionId
     * @return MitPaymentResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function createMitPaymentCommit(
        MitPaymentRequest $mitPayment,
        string $transactionId = ''
    ): MitPaymentResponse {
        $this->validateRequestItem($mitPayment);

        $uri = '/payments/' . $transactionId . '/token/commit';

        $mitPaymentResponse = $this->post(
            $uri,
            $mitPayment,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return MitPaymentResponse
             */
            function ($decoded) {
                return (new MitPaymentResponse())
                    ->setTransactionId($decoded->transactionId ?? null);
            },
            $transactionId
        );

        return $mitPaymentResponse;
    }

    /**
     * @param RevertPaymentAuthHoldRequest $revertPaymentAuthHoldRequest
     *
     * @return RevertPaymentAuthHoldResponse
     *
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function revertPaymentAuthorizationHold(
        RevertPaymentAuthHoldRequest $revertPaymentAuthHoldRequest
    ): RevertPaymentAuthHoldResponse {
        $this->validateRequestItem($revertPaymentAuthHoldRequest);
        $transactionId = $revertPaymentAuthHoldRequest->getTransactionId();

        $uri = '/payments/' . $transactionId . '/token/revert';

        $revertPaymentAuthHoldResponse = $this->post(
            $uri,
            $revertPaymentAuthHoldRequest,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return RevertPaymentAuthHoldResponse
             */
            function ($decoded) {
                return (new RevertPaymentAuthHoldResponse())
                    ->setTransactionId($decoded->transactionId ?? null);
            },
            $transactionId
        );

        return $revertPaymentAuthHoldResponse;
    }

    /**
     * Get settlements for merchant
     *
     * @param SettlementRequest $settlementRequest
     * @return mixed
     * @throws HmacException
     * @throws ValidationException
     */
    public function requestSettlements(SettlementRequest $settlementRequest)
    {
        $this->validateRequestItem($settlementRequest);

        $uri = '/settlements';

        $query = http_build_query($settlementRequest->jsonSerialize());

        if (!empty($query)) {
            $uri .= '?' . $query;
        }

        return $this->get(
            $uri,
            function ($decoded) {
                return (new SettlementResponse())
                    ->setSettlements($decoded);
            }
        );
    }

    /**
     * Get settlements for merchant
     *
     * @param string|null $startDate Start date in Y-m-d format
     * @param string|null $endDate End date in Y-m-d format
     * @param string|null $reference
     * @param int|null $limit
     * @param int|null $subMerchant
     * @return SettlementResponse
     * @throws HmacException
     *
     * @deprecated Method deprecated, use requestSettlements()
     */
    public function getSettlements(
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $reference = null,
        ?int $limit = null,
        ?int $subMerchant = null
    ) {
        if ($startDate) {
            if ((new \DateTime())->createFromFormat('Y-m-d', $startDate) == false) {
                throw new ValidationException('startDate must be in Y-m-d format');
            }
        }

        if ($endDate) {
            if ((new \DateTime())->createFromFormat('Y-m-d', $endDate) == false) {
                throw new ValidationException('endDate must be in Y-m-d format');
            }
        }

        $uri = "/settlements";

        $parameters = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'bankReference' => $reference,
            'limit' => $limit,
            'submerchant' => $subMerchant,
        ];

        $query = http_build_query(array_filter($parameters));

        if (!empty($query)) {
            $uri .= '?' . $query;
        }

        return $this->get(
            $uri,
            function ($decoded) {
                return (new SettlementResponse())
                    ->setSettlements($decoded);
            }
        );
    }

    /**
     * Request payment report.
     * Report is sent to callbackUrl defined in ReportRequest.
     *
     * @param ReportRequest $reportRequest
     * @return ReportRequestResponse
     * @throws HmacException
     * @throws ValidationException
     */
    public function requestPaymentReport(ReportRequest $reportRequest): ReportRequestResponse
    {
        $this->validateRequestItem($reportRequest);
        $uri = '/payments/report';

        $reportRequestResponse = $this->post(
            $uri,
            $reportRequest,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return ReportRequestResponse
             */
            function ($decoded) {
                return (new ReportRequestResponse())
                    ->setRequestId($decoded->requestId ?? null);
            },
        );

        return $reportRequestResponse;
    }

    /**
     * Request payment report by settlement ID.
     *
     * @param ReportBySettlementRequest $reportRequest
     * @param int $settlementId
     * @return ReportRequestResponse
     * @throws HmacException
     * @throws ValidationException
     */
    public function requestPaymentReportBySettlement(
        ReportBySettlementRequest $reportRequest,
        int $settlementId
    ): ReportRequestResponse {
        $this->validateRequestItem($reportRequest);
        $uri = "/settlements/{$settlementId}/payments/report";

        return $this->post(
            $uri,
            $reportRequest,
            function ($decoded) {
                return (new ReportRequestResponse())
                    ->setRequestId($decoded->requestId ?? null);
            },
        );
    }

    /**
     * Activate invoice created with manualInvoiceActivation set to true
     *
     * @param string $transactionId
     * @return InvoiceActivationResponse
     * @throws HmacException
     */
    public function activateInvoice(string $transactionId)
    {
        $uri = "/payments/{$transactionId}/activate-invoice";

        return $this->post(
            $uri,
            null,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return InvoiceActivationResponse
             */
            function ($decoded) {
                return (new InvoiceActivationResponse())
                    ->setStatus($decoded->status);
            },
            $transactionId
        );
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
    public function validateHmac(array $response = [], string $body = '', string $signature = ''): void
    {
        Signature::validateHmac($response, $body, $signature, $this->secretKey);
    }

    /**
     * Create a CIT payment request.
     *
     * @param CitPaymentRequest $citPayment A CIT payment class instance.
     * @param string $uri The uri for the request.
     *
     * @return CitPaymentResponse
     * @throws HmacException Thrown if HMAC calculation fails for responses.
     * @throws ClientException A Guzzle HTTP client exception is thrown for erroneous requests.
     * @throws ValidationException Thrown if payment validation fails.
     */
    private function createCitPayment(CitPaymentRequest $citPayment, string $uri): CitPaymentResponse
    {
        $this->validateRequestItem($citPayment);

        try {
            $citPaymentResponse = $this->post(
                $uri,
                $citPayment,
                /**
                 * Create the response instance.
                 *
                 * @param mixed $decoded The decoded body.
                 * @return CitPaymentResponse
                 */
                function ($decoded) {
                    return (new CitPaymentResponse())
                        ->setTransactionId($decoded->transactionId ?? null)
                        ->setThreeDSecureUrl($decoded->threeDSecureUrl ?? null);
                }
            );

            return $citPaymentResponse;
        } catch (ClientException $e) {
            if ($e->getResponseBody() && $e->getResponseCode() === 403) {
                $decoded = json_decode($e->getResponseBody());
                return (new CitPaymentResponse())
                    ->setTransactionId($decoded->transactionId ?? null)
                    ->setThreeDSecureUrl($decoded->threeDSecureUrl ?? null);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param MitPaymentRequest $mitPayment
     * @param string $uri
     * @return MitPaymentResponse
     * @throws HmacException
     * @throws ValidationException
     */
    private function createMitPayment(MitPaymentRequest $mitPayment, string $uri): MitPaymentResponse
    {
        $this->validateRequestItem($mitPayment);

        $mitPaymentResponse = $this->post(
            $uri,
            $mitPayment,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return MitPaymentResponse
             */
            function ($decoded) {
                return (new MitPaymentResponse())
                    ->setTransactionId($decoded->transactionId ?? null);
            }
        );

        return $mitPaymentResponse;
    }
}
