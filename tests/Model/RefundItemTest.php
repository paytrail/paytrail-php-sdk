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


    public function testRefundItemIsValid()
    {
        $refundItem = (new RefundItem())->setAmount(123)
            ->setStamp('thisIsStamp');

        $this->assertEquals(true, $refundItem->validate());
    }

    public function testRefundItemWithoutAmountThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new RefundItem())->setStamp('thisIsStamp')
            ->validate();
    }

    public function testRefundItemWithNegativeAmountThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new RefundItem())->setAmount(-1)
            ->setStamp('thisIsStamp')
            ->validate();
    }
}
