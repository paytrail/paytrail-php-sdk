<?php

declare(strict_types=1);

namespace Tests\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Commission;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    public function testValidity()
    {
        $commission = new Commission();
        $this->assertInstanceOf(Commission::class, $commission);
        $commission->setMerchant('123456');
        $commission->setAmount(100);
        $this->assertIsBool($commission->validate(), 'merchant & amount are valid');
    }

    public function testMerchantExceptions()
    {
        $this->expectException(ValidationException::class);
        $commission = new Commission();
        $commission->setAmount(123);
        $commission->setMerchant('');
        $commission->validate();
    }

    public function testAmountExceptions()
    {
        // Test fail
        $this->expectException(ValidationException::class);
        $commission = new Commission();
        $commission->setMerchant('123456');
        $commission->validate();
    }
}
