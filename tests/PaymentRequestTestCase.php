<?php

declare(strict_types=1);

namespace Tests;

use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\HmacException;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Interfaces\PaymentRequestInterface;
use Paytrail\SDK\Model\PaymentMethodGroup;
use Paytrail\SDK\Request\PaymentRequest;
use Paytrail\SDK\Request\ShopInShopPaymentRequest;
use Paytrail\SDK\Response\PaymentResponse;
use PHPUnit\Framework\TestCase;

abstract class PaymentRequestTestCase extends TestCase
{
    protected const SECRET = 'SAIPPUAKAUPPIAS';
    protected const MERCHANT_ID = 375917;
    protected const SHOP_IN_SHOP_SECRET = 'MONISAIPPUAKAUPPIAS';
    protected const SHOP_IN_SHOP_AGGREGATE_MERCHANT_ID = 695861;
    protected const SHOP_IN_SHOP_SUB_MERCHANT_ID = '695874';
    protected const COF_PLUGIN_VERSION = 'phpunit-test';

    /**
     * Paytrail client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * Paytrail client instance for shop-in-shop requests.
     *
     * @var Client
     */
    protected $sisClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);
        $this->sisClient = new Client(
            self::SHOP_IN_SHOP_AGGREGATE_MERCHANT_ID,
            self::SHOP_IN_SHOP_SECRET,
            self::COF_PLUGIN_VERSION
        );
    }

    /**
     * Create a payment request.
     *
     * @param PaymentRequest|PaymentRequestInterface $paymentRequest
     * @return PaymentResponse
     */
    protected function createPayment(PaymentRequestInterface $paymentRequest): PaymentResponse
    {
        try {
            $paymentRequest->validate();

            return $this->client->createPayment($paymentRequest);
        } catch (HmacException | ValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * Create a shop-in-shop payment request.
     *
     * @param ShopInShopPaymentRequest|PaymentRequestInterface $paymentRequest
     * @return PaymentResponse
     */
    protected function createShopInShopPayment(PaymentRequestInterface $paymentRequest): PaymentResponse
    {
        try {
            $paymentRequest->validate();

            return $this->sisClient->createShopInShopPayment($paymentRequest);
        } catch (HmacException | ValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * Asserts that a payment response is valid and has all the required fields.
     *
     * @param PaymentResponse $paymentResponse
     * @return void
     */
    public static function assertPaymentResponseIsValid(PaymentResponse $paymentResponse): void
    {
        static::assertNotEmpty($paymentResponse->getTransactionId());
        static::assertNotEmpty($paymentResponse->getHref());
        static::assertNotEmpty($paymentResponse->getTerms());
        static::assertNotEmpty($paymentResponse->getReference());
        static::assertIsArray($paymentResponse->getGroups());
        static::assertContainsOnlyInstancesOf(PaymentMethodGroup::class, $paymentResponse->getGroups());
        static::assertIsArray($paymentResponse->getProviders());
    }
}
