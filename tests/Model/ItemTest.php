<?php

declare(strict_types=1);

namespace Tests\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testIsItemValid()
    {
        $item = (new Item())->setUnitPrice(1)
            ->setUnits(2)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(0)
            ->setProductCode('productCode123')
            ->setDeliveryDate('2023-01-01')
            ->setDescription('description');

        $this->assertTrue($item->validate());
    }

    public function testItemWithoutUnitPriceThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new Item())->setUnits(2)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(0)
            ->setProductCode('productCode123')
            ->setDescription('description')
            ->validate();
    }

    public function testItemWithoutUnitsTrowsError()
    {
        $this->expectException(ValidationException::class);
        (new Item())->setUnitPrice(1)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(0)
            ->setProductCode('productCode123')
            ->setDescription('description')
            ->validate();
    }

    public function testItemWithNegativeUnitsThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new Item())->setUnitPrice(1)
            ->setUnits(-1)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(0)
            ->setProductCode('productCode123')
            ->setDescription('description')
            ->validate();
    }

    public function testItemWIthNegativeVatThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new Item())->setUnitPrice(1)
            ->setUnits(2)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(-10)
            ->setProductCode('productCode123')
            ->setDescription('description')
            ->validate();
    }

    public function testItemWithoutProductCodeThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new Item())->setUnitPrice(1)
            ->setUnits(2)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(10)
            ->setDescription('description')
            ->validate();
    }

    public static function providerForUnitPriceLimitValues()
    {
        return [
            'Negative amount'     => [-1, true],
            'Zero amount'         => [0, true],
            'Maximum amount'      => [99999999, true],
            'Over maximum amount' => [100000000, false]
        ];
    }

    public static function providerForUnitPriceLimitValuesShopInShop()
    {
        return [
            'Negative amount'     => [-1, false],
            'Zero amount'         => [0, true],
            'Maximum amount'      => [99999999, true],
            'Over maximum amount' => [100000000, false]
        ];
    }

    /**
     * @dataProvider providerForUnitPriceLimitValues
     */
    public function testUnitPriceLimitValues($unitPrice, $expectedResult)
    {
        $item = (new Item())->setUnitPrice($unitPrice)
            ->setUnits(2)
            ->setStamp('thisIsStamp')
            ->setVatPercentage(0)
            ->setProductCode('productCode123')
            ->setDeliveryDate('2023-01-01')
            ->setDescription('description');

        try {
            $validationResult = $item->validate();
        } catch (ValidationException $exception) {
            $validationResult = false;
        }

        $this->assertEquals($expectedResult, $validationResult);
    }

    public function testValidateShopInShopThrowsExceptionWhenMerchantIsEmpty(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('merchant is empty');

        $item = (new Item())
            ->setUnitPrice(213)
            ->setUnits(2)
            ->setProductCode('pr1')
            ->validateShopInShop();
    }

    /**
     * @dataProvider providerForUnitPriceLimitValuesShopInShop
     */
    public function testValidateShopInShopThrowsExceptionWhenUnitPriceIsNegative($unitPrice, $expectedResult): void
    {
        $item = new Item();
        $item->setUnitPrice($unitPrice);
        $item->setUnits(2);
        $item->setProductCode('pr1');
        $item->setMerchant('merchant1');


        try {
            $item->validate();
            $validationResult = $item->validateShopInShop();
        } catch (ValidationException $exception) {
            $validationResult = false;
        }

        $this->assertEquals($expectedResult, $validationResult);

    }
}
