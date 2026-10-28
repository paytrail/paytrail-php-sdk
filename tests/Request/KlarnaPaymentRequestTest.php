<?php

declare(strict_types=1);

namespace Tests\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\KlarnaProviderDetails;
use Paytrail\SDK\Model\ProviderDetails;
use Paytrail\SDK\Request\KlarnaPaymentRequest;
use PHPUnit\Framework\TestCase;

class KlarnaPaymentRequestTest extends TestCase
{
    private function createRequest(): KlarnaPaymentRequest
    {
        $request = new KlarnaPaymentRequest();
        $request->setAmount(30)
            ->setStamp('RequestStamp')
            ->setReference('RequestReference123')
            ->setCurrency('EUR')
            ->setLanguage('FI')
            ->setCustomer((new Customer())->setEmail('customer@email.com'))
            ->setRedirectUrls(
                (new CallbackUrl())
                    ->setSuccess('https://someother.com/success')
                    ->setCancel('https://someother.com/cancel')
            );

        return $request;
    }

    public function testValidWithNetworkSessionToken()
    {
        $request = $this->createRequest();
        $request->setProviderDetails(
            (new ProviderDetails())->setKlarna(
                (new KlarnaProviderDetails())
                    ->setNetworkSessionToken('krn:network:eu1:test:session-token:abc')
                    ->setNetworkData('{"content_type":"application/vnd.klarna.example+json"}')
            )
        );

        $this->assertTrue($request->validate());

        $json = json_decode(json_encode($request), true);
        $this->assertSame(
            'krn:network:eu1:test:session-token:abc',
            $json['providerDetails']['klarna']['networkSessionToken']
        );
    }

    public function testThrowsWithoutNetworkSessionToken()
    {
        $this->expectException(ValidationException::class);
        $this->createRequest()->validate();
    }

    public function testThrowsWhenNetworkSessionTokenTooLong()
    {
        $request = $this->createRequest();
        $request->setProviderDetails(
            (new ProviderDetails())->setKlarna(
                (new KlarnaProviderDetails())->setNetworkSessionToken(str_repeat('a', 8193))
            )
        );

        $this->expectException(ValidationException::class);
        $request->validate();
    }
}
