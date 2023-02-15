<?php

declare(strict_types=1);

namespace Tests\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Interfaces\PaymentRequestInterface;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Request\PaymentRequest;
use PHPUnit\Framework\TestCase;

class PaymentRequestTest extends TestCase
{
    public function testPaymentRequestWithItems(): void
    {
        $paymentRequest = $this->getPaymentRequest();

        $paymentRequest->setItems($this->getPaymentItems());

        try {
            $this->assertEquals(true, $paymentRequest->validate());
        } catch (ValidationException $e) {
            $this->fail();
        }
    }

    public function testPaymentRequestWithoutItems(): void
    {
        $paymentRequest = $this->getPaymentRequest();

        try {
            $this->assertEquals(true, $paymentRequest->validate());
        } catch (ValidationException $e) {
            $this->fail();
        }
    }

    private function getPaymentRequest(): PaymentRequestInterface
    {
        $payment = (new PaymentRequest())
            ->setAmount(30)
            ->setStamp('RequestStamp')
            ->setReference('RequestReference123')
            ->setCurrency('EUR')
            ->setLanguage('EN');

        $customer = (new Customer())
            ->setEmail('customer@email.com');

        $payment->setCustomer($customer);

        $callback = (new CallbackUrl())
            ->setCancel('https://somedomain.com/cancel')
            ->setSuccess('https://somedomain.com/success');

        $payment->setCallbackUrls($callback);

        $redirect = (new CallbackUrl())
            ->setSuccess('https://someother.com/success')
            ->setCancel('https://someother.com/cancel');

        $payment->setRedirectUrls($redirect);

        return $payment;
    }

    private function getPaymentItems(): array
    {
        return [
            (new Item())
                ->setStamp('someStamp')
                ->setDeliveryDate('12.12.2020')
                ->setProductCode('pr1')
                ->setVatPercentage(25)
                ->setUnitPrice(10)
                ->setUnits(1),
            (new Item())
                ->setStamp('someOtherStamp')
                ->setDeliveryDate('12.12.2020')
                ->setProductCode('pr2')
                ->setVatPercentage(25)
                ->setUnitPrice(10)
                ->setUnits(2),
        ];
    }
}
