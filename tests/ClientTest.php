<?php

declare(strict_types=1);

namespace Tests;

use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\ClientException;
use Paytrail\SDK\Exception\HmacException;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Address;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\Commission;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Request\AddCardFormRequest;
use Paytrail\SDK\Request\CitPaymentRequest;
use Paytrail\SDK\Request\GetTokenRequest;
use Paytrail\SDK\Request\MitPaymentRequest;
use Paytrail\SDK\Request\PaymentRequest;
use Paytrail\SDK\Request\PaymentStatusRequest;
use Paytrail\SDK\Request\ReportRequest;
use Paytrail\SDK\Request\RevertPaymentAuthHoldRequest;
use Paytrail\SDK\Request\SettlementRequest;
use Paytrail\SDK\Request\ShopInShopPaymentRequest;
use Paytrail\SDK\Model\Provider;

class ClientTest extends PaymentRequestTestCase
{
    private $item;
    private $item2;
    private $shopInShopItem;
    private $shopInShopItem2;
    private $redirect;
    private $callback;
    private $customer;
    private $address;
    private $paymentRequest;
    private $shopInShopPaymentRequest;
    private $citPaymentRequest;
    private $mitPaymentRequest;


    protected function setUp(): void
    {
        parent::setUp();

        $this->item = (new Item())
            ->setProductCode('pr1')
            ->setVatPercentage(24)
            ->setReference('itemReference123')
            ->setStamp('itemStamp-1' . rand(1, 999999))
            ->setUnits(1)
            ->setDescription('some description')
            ->setUnitPrice(100);

        $this->item2 = (new Item())
            ->setDeliveryDate('2020-12-12')
            ->setProductCode('pr2')
            ->setVatPercentage(24)
            ->setReference('itemReference123')
            ->setStamp('itemStamp-2' . rand(1, 999999))
            ->setUnits(2)
            ->setDescription('some description2')
            ->setUnitPrice(200);

        $this->shopInShopItem = clone $this->item;
        $this->shopInShopItem->setMerchant(self::SHOP_IN_SHOP_SUB_MERCHANT_ID);
        $this->shopInShopItem->setCommission(
            (new Commission())
                ->setAmount(10)
                ->setMerchant(self::SHOP_IN_SHOP_SUB_MERCHANT_ID)
        );

        $this->shopInShopItem2 = clone $this->item2;
        $this->shopInShopItem2->setMerchant(self::SHOP_IN_SHOP_SUB_MERCHANT_ID);
        $this->shopInShopItem2->setCommission(
            (new Commission())
                ->setAmount(5)
                ->setMerchant(self::SHOP_IN_SHOP_SUB_MERCHANT_ID)
        );

        $this->redirect = (new CallbackUrl())
            ->setCancel('https://somedomain.com/cancel')
            ->setSuccess('https://somedomain.com/success');

        $this->callback = (new CallbackUrl())
            ->setCancel('https://callbackdomain.com/cancel')
            ->setSuccess('https://callbackdomain.com/success');

        $this->customer = (new Customer())
            ->setEmail('customer@customerdomain.com');

        $this->address = (new Address())
            ->setStreetAddress('Hämeenkatu 12')
            ->setCity('Tampere')
            ->setCountry('Finland')
            ->setPostalCode('33200');

        $this->paymentRequest = (new PaymentRequest())
            ->setCustomer($this->customer)
            ->setRedirectUrls($this->redirect)
            ->setCallbackUrls($this->callback)
            ->setItems([$this->item, $this->item2])
            ->setAmount(500)
            ->setStamp('PaymentRequestStamp' . rand(1, 999999))
            ->setReference('PaymentRequestReference' . rand(1, 999999))
            ->setCurrency('EUR')
            ->setLanguage('EN')
            ->setDeliveryAddress($this->address)
            ->setInvoicingAddress($this->address);

        $this->shopInShopPaymentRequest = (new ShopInShopPaymentRequest())
            ->setCustomer($this->customer)
            ->setRedirectUrls($this->redirect)
            ->setCallbackUrls($this->callback)
            ->setItems([$this->shopInShopItem, $this->shopInShopItem2])
            ->setAmount(500)
            ->setStamp('PaymentRequestStamp' . rand(1, 999999))
            ->setReference('PaymentRequestReference' . rand(1, 999999))
            ->setCurrency('EUR')
            ->setLanguage('EN')
            ->setDeliveryAddress($this->address)
            ->setInvoicingAddress($this->address);

        $this->citPaymentRequest = (new CitPaymentRequest())
            ->setCustomer($this->customer)
            ->setRedirectUrls($this->redirect)
            ->setCallbackUrls($this->callback)
            ->setItems([$this->item, $this->item2])
            ->setAmount(500)
            ->setStamp('PaymentRequestStamp' . rand(1, 999999))
            ->setReference('PaymentRequestReference' . rand(1, 999999))
            ->setCurrency('EUR')
            ->setLanguage('FI')
            ->setDeliveryAddress($this->address)
            ->setInvoicingAddress($this->address);

        $this->mitPaymentRequest = (new MitPaymentRequest())
            ->setCustomer($this->customer)
            ->setRedirectUrls($this->redirect)
            ->setCallbackUrls($this->callback)
            ->setItems([$this->item, $this->item2])
            ->setAmount(500)
            ->setStamp('PaymentRequestStamp' . rand(1, 999999))
            ->setReference('PaymentRequestReference' . rand(1, 999999))
            ->setCurrency('EUR')
            ->setLanguage('FI')
            ->setDeliveryAddress($this->address)
            ->setInvoicingAddress($this->address);
    }

    public function testPaymentRequest()
    {
        $response = $this->createPayment($this->paymentRequest);
        $this->assertPaymentResponseIsValid($response);

        $transactionId = $response->getTransactionId();

        // Test payment status request with the transactionId we got from the PaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        try {
            $res = $this->client->getPaymentStatus($psr);
            $this->assertEquals('new', $res->getStatus());
            $this->assertEquals($transactionId, $res->getTransactionId());
        } catch (HmacException | ValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testShopInShopPaymentRequest()
    {
        $response = $this->createShopInShopPayment($this->shopInShopPaymentRequest);
        $this->assertPaymentResponseIsValid($response);

        $transactionId = $response->getTransactionId();

        // Test payment status request with the transactionId we got from the PaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        try {
            $res = $this->sisClient->getPaymentStatus($psr);
            $this->assertEquals('new', $res->getStatus());
            $this->assertEquals($transactionId, $res->getTransactionId());
        } catch (HmacException | ValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testAddCardFormRequest()
    {
        $datetime = new \DateTime();

        $client = $this->client;

        $addCardFormRequest = (new AddCardFormRequest())
            ->setCheckoutAccount(self::MERCHANT_ID)
            ->setCheckoutAlgorithm('sha256')
            ->setCheckoutMethod('POST')
            ->setCheckoutNonce(uniqid('', true))
            ->setCheckoutTimestamp($datetime->format('Y-m-d\TH:i:s.u\Z'))
            ->setCheckoutRedirectSuccessUrl('https://somedomain.com/success')
            ->setCheckoutRedirectCancelUrl('https://somedomain.com/cancel')
            ->setLanguage('EN')
            ->setSignature('d902e82ee61cb2c6ff2ba48b255402eb5d446c943e8ebbb3ada4fe40be7b8ab5');

        $this->assertInstanceOf(AddCardFormRequest::class, $addCardFormRequest);
        $this->assertTrue($addCardFormRequest->validate());
        $response = $client->createAddCardFormRequest($addCardFormRequest);

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testGetTokenRequest()
    {
        //  To update after 11/2026
        //  Get new tokenization_id, card and customer details.
        //  'add-card' action will return tokenization_id,
        //  and with tokenization_id we could get card and customer details.
        //  Card used: 0024 https://docs.paytrail.com/#/payment-method-providers?id=test-cards-for-payments

        $checkoutTokenizationId = 'b34e5821-2a85-4840-8b27-21ef81168bec';

        $client = $this->client;

        $getTokenRequest = (new GetTokenRequest())
            ->setCheckoutTokenizationId($checkoutTokenizationId);

        $this->assertInstanceOf(GetTokenRequest::class, $getTokenRequest);
        $this->assertTrue($getTokenRequest->validate());
        $response = $client->createGetTokenRequest($getTokenRequest);

        $responseJsonData = $response->jsonSerialize();

        $expectedArray = [
            'token' => '798b445a-2216-46b7-ad1a-000f40ced6e8',
            'card' => [
                'type' => 'Visa',
                'bin' => '415301',
                'partial_pan' => '0024',
                'expire_year' => '2026',
                'expire_month' => '11',
                'cvc_required' => 'not_tested',
                'funding' => 'debit',
                'category' => 'unknown',
                'country_code' => 'FI',
                'pan_fingerprint' => '693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce',
                'card_fingerprint' => '24973f9037d418c0258ee61d90970c15a1c434a457d3974c9cdc12742f87c673'
            ],
            'customer' => [
                'network_address' => '89.35.145.204',
                'country_code' => 'PL'
            ]
        ];

        $this->assertNotEmpty($response->getToken());
        $this->assertNotEmpty($response->getCard());
        $this->assertNotEmpty($response->getCustomer());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($responseJsonData));
    }

    public function testCitPaymentRequestCharge()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentCharge($citPaymentRequest);

        $this->assertNotEmpty($response->getTransactionId());

        $transactionId = $response->getTransactionId();

        // Test payment status request with the transactionId we got from the CitPaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);

        $res = $client->getPaymentStatus($psr);
        $this->assertEquals('ok', $res->getStatus());
        $this->assertEquals($res->getTransactionId(), $transactionId);
    }

    public function testMitPaymentRequestCharge()
    {
        $client = $this->client;
        $paymentRequest = $this->mitPaymentRequest;

        $mitPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($mitPaymentRequest->validate());

        $response = $client->createMitPaymentCharge($mitPaymentRequest);

        $this->assertNotEmpty($response->getTransactionId());

        $transactionId = $response->getTransactionId();

        // Test payment status request with the transactionId we got from the MitPaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);

        $res = $client->getPaymentStatus($psr);
        $this->assertEquals('ok', $res->getStatus());
        $this->assertEquals($res->getTransactionId(), $transactionId);
    }

    public function testCitPaymentRequestCharge3DS()
    {
        //  To update after 11/2026
        //  Card required with 3DS
        //  Card: 0170 https://docs.paytrail.com/#/payment-method-providers?id=test-cards-for-payments

        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('8d3cb70a-7911-42c4-81cd-5318a5f269a4');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentCharge($citPaymentRequest);

        $this->assertNotEmpty($response->getTransactionId());
        $this->assertNotEmpty($response->getThreeDSecureUrl());
    }

    public function testCitPaymentRequestAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentAuthorizationHold($citPaymentRequest);

        $this->assertNotEmpty($response->getTransactionId());

        $transactionId = $response->getTransactionId();

        // Test payment status request with the transactionId we got from the CitPaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);

        $res = $client->getPaymentStatus($psr);
        $this->assertEquals('authorization-hold', $res->getStatus());
        $this->assertEquals($res->getTransactionId(), $transactionId);
    }

    public function testMitPaymentRequestAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->mitPaymentRequest;

        $mitPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($mitPaymentRequest->validate());

        $response = $client->createMitPaymentAuthorizationHold($mitPaymentRequest);

        $this->assertNotEmpty($response->getTransactionId());

        $transactionId = $response->getTransactionId();

        // Test payment status request with the transactionId we got from the MitPaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);

        $res = $client->getPaymentStatus($psr);
        $this->assertEquals('authorization-hold', $res->getStatus());
        $this->assertEquals($res->getTransactionId(), $transactionId);
    }

    public function testCitPaymentRequestAuthorizationHold3DS()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('8d3cb70a-7911-42c4-81cd-5318a5f269a4');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentAuthorizationHold($citPaymentRequest);

        $this->assertNotEmpty($response->getTransactionId());
        $this->assertNotEmpty($response->getThreeDSecureUrl());
    }

    public function testCitPaymentRequestCommit()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;
        $transactionId = 'c12e224e-806f-11ea-9de3-33451a6f6d70';

        $citPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentCommit($citPaymentRequest, $transactionId);
        $this->assertNotEmpty($response->getTransactionId());
    }

    public function testMitPaymentRequestCommit()
    {
        $client = $this->client;
        $paymentRequest = $this->mitPaymentRequest;
        $transactionId = 'c12e224e-806f-11ea-9de3-33451a6f6d70';

        $mitPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($mitPaymentRequest->validate());

        $response = $client->createMitPaymentCommit($mitPaymentRequest, $transactionId);
        $this->assertNotEmpty($response->getTransactionId());
    }

    public function testRevertCitPaymentAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($citPaymentRequest->validate());

        // Create new CitPaymentAuthorizationHold
        $response = $client->createCitPaymentAuthorizationHold($citPaymentRequest);
        $this->assertNotEmpty($response->getTransactionId());

        $transactionId = $response->getTransactionId();

        // Test reverting the previously created CitPaymentAuthorizationHold
        $revertCitPaymentAuthHoldRequest = (new RevertPaymentAuthHoldRequest())
            ->setTransactionId($transactionId);

        $this->assertTrue($revertCitPaymentAuthHoldRequest->validate());

        $revertResponse = $client->revertPaymentAuthorizationHold($revertCitPaymentAuthHoldRequest);
        $this->assertNotEmpty($revertResponse->getTransactionId());
    }

    public function testRevertMitPaymentAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->mitPaymentRequest;

        $mitPaymentRequest = $paymentRequest->setToken('798b445a-2216-46b7-ad1a-000f40ced6e8');

        $this->assertTrue($mitPaymentRequest->validate());

        // Create new CitPaymentAuthorizationHold
        $response = $client->createMitPaymentAuthorizationHold($mitPaymentRequest);
        $this->assertNotEmpty($response->getTransactionId());

        $transactionId = $response->getTransactionId();

        // Test reverting the previously created MitPaymentAuthorizationHold
        $revertCitPaymentAuthHoldRequest = (new RevertPaymentAuthHoldRequest())
            ->setTransactionId($transactionId);

        $this->assertTrue($revertCitPaymentAuthHoldRequest->validate());

        $revertResponse = $client->revertPaymentAuthorizationHold($revertCitPaymentAuthHoldRequest);
        $this->assertNotEmpty($revertResponse->getTransactionId());
    }

    public function testRevertCitPaymentAuthorizationHoldException()
    {
        $transactionId = '9ad16660-7fc5-11ea-955f-23b07377c608';

        $revertCitPaymentAuthHoldRequestException = (new RevertPaymentAuthHoldRequest())
            ->setTransactionId($transactionId);

        $this->assertTrue($revertCitPaymentAuthHoldRequestException->validate());

        try {
            $client = $this->client;
            $client->revertPaymentAuthorizationHold($revertCitPaymentAuthHoldRequestException);
        } catch (ClientException $e) {
            // Fails, as transaction with the given transactionID has already been reverted
            $this->assertStringContainsString('{"status":"error","message":"Transaction not found"}', $e->getMessage());
        }
    }

    public function testRequestSettlementsWithInvalidDateThrowsException()
    {
        $settlementRequest = (new SettlementRequest())->setStartDate('30.5.2022');
        $this->expectException(ValidationException::class);
        $this->client->requestSettlements($settlementRequest);
    }

    public function testRequestSettlementsReturnsValidResponse()
    {
        $settlementRequest = (new SettlementRequest());
        $settlementResponse = $this->client->requestSettlements($settlementRequest);
        $this->assertIsArray($settlementResponse->getSettlements());
    }

    public function testGetSettlementsReturnsValidResponse()
    {
        $settlementRequest = new SettlementRequest();
        $settlementResponse = $this->client->requestSettlements($settlementRequest);
        $this->assertIsArray($settlementResponse->getSettlements());
    }

    public function testGetGroupedPaymentProvidersAcceptsLanguageParameters()
    {
        $providers = $this->client->getGroupedPaymentProviders(100, 'EN');
        $this->assertIsArray($providers);
        // Get first provider groups providers and select first provider from array.
        $this->assertInstanceOf(Provider::class, $providers['groups'][0]['providers'][0]);
    }

    public function testRequestPaymentReportReturnsRequestId()
    {
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test');
        $response = $this->client->requestPaymentReport($reportRequest);

        $this->assertNotNull($response->getRequestId());
        $this->assertNotEmpty($response->getRequestId());
    }

    public function testRequestPaymentReportThrowsExceptionWhenRequestTypeIsEmpty()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setCallbackUrl('https://nourl.test');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenCallbackUrlIsEmpty()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWithInvalidPaymentStatus()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test')
            ->setPaymentStatus('Foobar');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenLimitExceeds()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test')
            ->setLimit(99999999);
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenLimitIsNegative()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test')
            ->setLimit(-500);
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenUrlInvalid()
    {
        $this->expectException(ClientException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('invalid-url');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenEndDateIsLowerThanStartDate()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test')
            ->setStartDate('2023-01-20T12:00:00+02:00')
            ->setEndDate('2023-01-01T23:59:50+02:00');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenStartDateIsInWrongFormat()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test')
            ->setStartDate('1.1.2023');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testRequestPaymentReportThrowsExceptionWhenEndDateIsInWrongFormat()
    {
        $this->expectException(ValidationException::class);
        $reportRequest = (new ReportRequest())
            ->setRequestType('json')
            ->setCallbackUrl('https://nourl.test')
            ->setEndDate('1.1.2023');
        $this->client->requestPaymentReport($reportRequest);
    }

    public function testPayAndAddCardReturnsTransactionIdAndUrl()
    {
        $response = $this->client->createPaymentAndAddCard($this->paymentRequest);
        $this->assertIsString($response->getTransactionId());
        $this->assertIsString($response->getRedirectUrl());
    }
}
