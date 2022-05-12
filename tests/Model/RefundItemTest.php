<?php
declare(strict_types=1);

namespace Tests\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\RefundItem;
use PHPUnit\Framework\TestCase;

class RefundItemTest extends TestCase
{
    public function testValidation()
    {
        $this->expectException(\TypeError::class);
        $rfi = new RefundItem();
        $rfi->setAmount(123.2322323);
    }

    public function testTypeError()
    {
        $this->expectException(\TypeError::class);
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
