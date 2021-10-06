<?php


use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testIsItemValid()
    {
        $i = new Item;

        $this->assertInstanceOf(
            Item::class,
            $i
        );

        $i->setUnitPrice(1)
            ->setUnits(2)
            ->setVatPercentage(0)
            ->setProductCode('productCode123')
            ->setDeliveryDate('12.12.1999')
            ->setDescription('description');

        $this->assertEquals(true, $i->validate());
    }

    public function testExceptions()
    {
        $this->expectException(ValidationException::class);
        $i = new Item;
        $i->validate();
    }

    public function testExceptionMessages()
    {
        $i = new Item;

        try {
            $i->validate();
        } catch (Exception $e) {
            $this->assertStringContainsString('UnitPrice is not an integer', $e->getMessage());
        }

        try {
            $i->setUnitPrice(2);
            $i->validate();
        } catch (Exception $e) {
            $this->assertStringContainsString('Units is not an integer', $e->getMessage());
        }

        try {
            $i->setUnits(3);
            $i->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('vatPercentage is not an integer', $e->getMessage());
        }

        try {
            $i->setVatPercentage(12);
            $i->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('productCode is empty', $e->getMessage());
        }

        try {
            $i->setProductCode('productCode123');
            $i->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('deliveryDate is empty', $e->getMessage());
        }

        $i->setDeliveryDate('12.12.1999');

        try {
            $this->assertIsBool($i->validate(), 'Item::validate returns bool');
        } catch (ValidationException $e) {
        }


    }

}