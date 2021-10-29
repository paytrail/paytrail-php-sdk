<?php

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Request\PaymentRequest;
use PHPUnit\Framework\TestCase;

class PaymentRequestTest extends TestCase
{
    public function testPaymentRequest()
    {
        $r = new PaymentRequest();
        $r->setAmount(30);
        $r->setStamp('RequestStamp');
        $r->setReference('RequestReference123');
        $r->setCurrency('EUR');
        $r->setLanguage('EN');

        $item1 = new Item();
        $item1->setStamp('someStamp')
            ->setProductCode('pr1')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setUnits(1);

        $item2 = new Item();
        $item2->setStamp('someOtherStamp')
            ->setProductCode('pr2')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setUnits(2);

        $r->setItems([$item1, $item2]);

        $c = new Customer();
        $c->setEmail('customer@email.com');

        $r->setCustomer($c);

        $cb = new CallbackUrl();
        $cb->setCancel('https://somedomain.com/cancel')
            ->setSuccess('https://somedomain.com/success');

        $r->setCallbackUrls($cb);

        $redirect = new CallbackUrl();
        $redirect->setSuccess('https://someother.com/success')
            ->setCancel('https://someother.com/cancel');

        $r->setRedirectUrls($redirect);

        try {
            $this->assertEquals(true, $r->validate());
        } catch (ValidationException $e) {
        }
    }
}
