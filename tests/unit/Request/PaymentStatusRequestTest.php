<?php


use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Request\PaymentStatusRequest;
use PHPUnit\Framework\TestCase;

class PaymentStatusRequestTest extends TestCase
{
    public function testPaymentStatusRequest()
    {
        $statusRequest = new PaymentStatusRequest();
        $statusRequest->setTransactionId('someString');
        $this->assertEquals(true, $statusRequest->validate());
    }

    public function testPaymentStatusRequestException()
    {
        $this->expectException(ValidationException::class);
        $statusRequest = new PaymentStatusRequest();
        $statusRequest->validate();
    }
}