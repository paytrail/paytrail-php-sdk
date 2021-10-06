<?php


use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\RefundItem;
use PHPUnit\Framework\TestCase;

class RefundItemTest extends TestCase
{
    public function testValidation()
    {
        $rfi = new RefundItem();
        $rfi->setAmount(123.2322323); // setter parameter typecasting should work for floats
        $this->assertEquals(123, $rfi->getAmount());
        $rfi->setStamp('someStringValueForTheStamp');
        $this->assertEquals(true, $rfi->validate());
    }

    public function testTypeError()
    {
        $this->expectException(TypeError::class);
        $rfi = new RefundItem();
        $rfi->setAmount("not a number");
    }

    public function testValidationExceptions()
    {
        $this->expectException(ValidationException::class);
        $rfi = new RefundItem();
        $rfi->setAmount(123);
        $rfi->setStamp('');
        $rfi->validate();
    }
}
