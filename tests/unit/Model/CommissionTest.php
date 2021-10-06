<?php

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Commission;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    public function testValidity()
    {
        $c = new Commission();
        $this->assertInstanceOf(Commission::class, $c);
        $c->setMerchant('123456');
        $c->setAmount(100);
        $this->assertIsBool($c->validate(), 'merchant & amount are valid');
    }

    public function testMerchantExceptions()
    {
        // Test fail
        $this->expectException(ValidationException::class);
        $c = new Commission();
        $c->setAmount(123);
        $c->setMerchant('');
        $c->validate();
    }

    public function testAmountExceptions()
    {
        // Test fail
        $this->expectException(ValidationException::class);
        $c = new Commission();
        $c->setMerchant('123456');
        var_dump($c->validate());
    }

} 
