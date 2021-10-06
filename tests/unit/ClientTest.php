<?php

use Guzzle6\Exception\RequestException;
use OpMerchantServices\SDK\Client;
use OpMerchantServices\SDK\Exception\HmacException;
use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Address;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Request\AddCardFormRequest;
use OpMerchantServices\SDK\Request\CitPaymentRequest;
use OpMerchantServices\SDK\Request\GetTokenRequest;
use OpMerchantServices\SDK\Request\MitPaymentRequest;
use OpMerchantServices\SDK\Request\PaymentRequest;
use OpMerchantServices\SDK\Request\PaymentStatusRequest;
use OpMerchantServices\SDK\Request\RevertPaymentAuthHoldRequest;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    const SECRET = 'SAIPPUAKAUPPIAS';

    const MERCHANT_ID = 375917;

    const COF_PLUGIN_VERSION = 'phpunit-test';

    protected $args;

    protected $client;

    protected $item;

    protected $item2;

    protected $redirect;

    protected $callback;

    protected $customer;

    protected $address;

    protected $paymentRequest;

    protected $citPaymentRequest;

    protected $mitPaymentRequest;

    protected function setUp(): void
    {
        $this->args = ['timeout' => 20];

        $this->client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION, $this->args);

        $this->item = (new Item())
            ->setDeliveryDate('2020-12-12')
            ->setProductCode('pr1')
            ->setVatPercentage(24)
            ->setReference('itemReference123')
            ->setStamp('itemStamp-' . rand(1, 999999))
            ->setUnits(1)
            ->setDescription('some description')
            ->setUnitPrice(100);

        $this->item2 = (new Item())
            ->setDeliveryDate('2020-12-12')
            ->setProductCode('pr2')
            ->setVatPercentage(24)
            ->setReference('itemReference123')
            ->setStamp('itemStamp-' . rand(1, 999999))
            ->setUnits(2)
            ->setDescription('some description2')
            ->setUnitPrice(200);

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
        $client = $this->client;
        $paymentRequest = $this->paymentRequest;

        $transactionId = '';

        if ($paymentRequest->validate()) {
            try {
                $response = $client->createPayment($paymentRequest);

                $this->assertObjectHasAttribute('transactionId', $response);
                $this->assertObjectHasAttribute('href', $response);
                $this->assertObjectHasAttribute('providers', $response);
                $this->assertIsArray($response->getProviders());

                $transactionId = $response->getTransactionId();

            } catch (HmacException $e) {
                var_dump($e->getMessage());
            } catch (ValidationException $e) {
                var_dump($e->getMessage());
            } catch (RequestException $e) {
                var_dump(json_decode($e->getResponse()->getBody()));
            }

        } else {
            echo 'PaymentRequest is not valid';
        }

        // Test payment status request with the transactionId we got from the PaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);

        try {
            $res = $client->getPaymentStatus($psr);
            $this->assertEquals('new', $res->getStatus());
            $this->assertEquals($res->getTransactionId(), $transactionId);
        } catch (HmacException $e) {
            var_dump('hmac error');
        } catch (ValidationException $e) {
            var_dump('validation error');
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
            ->setCheckoutNonce(uniqid(true))
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
        $checkoutTokenizationId = '818c478e-5682-46bf-97fd-b9c2b93a3fcd';

        $client = $this->client;

        $getTokenRequest = (new GetTokenRequest())
            ->setCheckoutTokenizationId($checkoutTokenizationId);

        $this->assertInstanceOf(GetTokenRequest::class, $getTokenRequest);
        $this->assertTrue($getTokenRequest->validate());
        $response = $client->createGetTokenRequest($getTokenRequest);

        $responseJsonData = $response->jsonSerialize();

        $expectedArray = [
            'token' => 'c7441208-c2a1-4a10-8eb6-458bd8eaa64f',
            'card' => [
                'type' => 'Visa',
                'bin' => '415301',
                'partial_pan' => '0024',
                'expire_year' => '2023',
                'expire_month' => '11',
                'cvc_required' => 'no',
                'funding' => 'debit',
                'category' => 'unknown',
                'country_code' => 'FI',
                'pan_fingerprint' => '693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce',
                'card_fingerprint' => 'c34cdd1952deb81734c012fbb11eabc56c4d61d198f28b448327ccf13f45417f'
            ],
            'customer' => [
                'network_address' => '93.174.192.154',
                'country_code' => 'FI'
            ]
        ];

        $this->assertObjectHasAttribute('token', $response);
        $this->assertObjectHasAttribute('card', $response);
        $this->assertObjectHasAttribute('customer', $response);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($responseJsonData));
    }

    public function testCitPaymentRequestCharge()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentCharge($citPaymentRequest);

        $this->assertObjectHasAttribute('transactionId', $response);

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

        $mitPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($mitPaymentRequest->validate());

        $response = $client->createMitPaymentCharge($mitPaymentRequest);

        $this->assertObjectHasAttribute('transactionId', $response);

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
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('40037d79-5c7f-4ffe-bf86-2d2025b64c36');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentCharge($citPaymentRequest);

        $this->assertObjectHasAttribute('transactionId', $response);
        $this->assertObjectHasAttribute('threeDSecureUrl', $response);
    }

    public function testCitPaymentRequestAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentAuthorizationHold($citPaymentRequest);

        $this->assertObjectHasAttribute('transactionId', $response);

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

        $mitPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($mitPaymentRequest->validate());

        $response = $client->createMitPaymentAuthorizationHold($mitPaymentRequest);

        $this->assertObjectHasAttribute('transactionId', $response);

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

        $citPaymentRequest = $paymentRequest->setToken('40037d79-5c7f-4ffe-bf86-2d2025b64c36');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentAuthorizationHold($citPaymentRequest);

        $this->assertObjectHasAttribute('transactionId', $response);
        $this->assertObjectHasAttribute('threeDSecureUrl', $response);
    }

    public function testCitPaymentRequestCommit()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;
        $transactionId = 'c12e224e-806f-11ea-9de3-33451a6f6d70';

        $citPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($citPaymentRequest->validate());

        $response = $client->createCitPaymentCommit($citPaymentRequest, $transactionId);
        $this->assertObjectHasAttribute('transactionId', $response);
    }

    public function testMitPaymentRequestCommit()
    {
        $client = $this->client;
        $paymentRequest = $this->mitPaymentRequest;
        $transactionId = 'c12e224e-806f-11ea-9de3-33451a6f6d70';

        $mitPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($mitPaymentRequest->validate());

        $response = $client->createMitPaymentCommit($mitPaymentRequest, $transactionId);
        $this->assertObjectHasAttribute('transactionId', $response);
    }

    public function testRevertCitPaymentAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->citPaymentRequest;

        $citPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($citPaymentRequest->validate());

        // Create new CitPaymentAuthorizationHold
        $response = $client->createCitPaymentAuthorizationHold($citPaymentRequest);
        $this->assertObjectHasAttribute('transactionId', $response);

        $transactionId = $response->getTransactionId();

        // Test reverting the previously created CitPaymentAuthorizationHold
        $revertCitPaymentAuthHoldRequest = (new RevertPaymentAuthHoldRequest())
            ->setTransactionId($transactionId);

        $this->assertTrue($revertCitPaymentAuthHoldRequest->validate());

        $revertResponse = $client->revertPaymentAuthorizationHold($revertCitPaymentAuthHoldRequest);
        $this->assertObjectHasAttribute('transactionId', $revertResponse);
    }

    public function testRevertMitPaymentAuthorizationHold()
    {
        $client = $this->client;
        $paymentRequest = $this->mitPaymentRequest;

        $mitPaymentRequest = $paymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $this->assertTrue($mitPaymentRequest->validate());

        // Create new CitPaymentAuthorizationHold
        $response = $client->createMitPaymentAuthorizationHold($mitPaymentRequest);
        $this->assertObjectHasAttribute('transactionId', $response);

        $transactionId = $response->getTransactionId();

        // Test reverting the previously created MitPaymentAuthorizationHold
        $revertCitPaymentAuthHoldRequest = (new RevertPaymentAuthHoldRequest())
            ->setTransactionId($transactionId);

        $this->assertTrue($revertCitPaymentAuthHoldRequest->validate());

        $revertResponse = $client->revertPaymentAuthorizationHold($revertCitPaymentAuthHoldRequest);
        $this->assertObjectHasAttribute('transactionId', $revertResponse);
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
        } catch (\GuzzleHttp6\Exception\ClientException $e) {
            // Fails, as transaction with the given transactionID has already been reverted
            $this->assertStringContainsString('{"status":"error","message":"Transaction not found"}', $e->getMessage());
        }
    }
}
