<?php

declare(strict_types=1);

namespace Tests\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\Commission;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Request\ShopInShopPaymentRequest;
use PHPUnit\Framework\TestCase;

class ShopInShopPaymentRequestTest extends TestCase
{
    public function testShopInShopPaymentRequest()
    {
        $r = new ShopInShopPaymentRequest();
        $r->setAmount(30);
        $r->setStamp('RequestStamp');
        $r->setReference('RequestReference123');
        $r->setCurrency('EUR');
        $r->setLanguage('EN');

        $com1 = new Commission();
        $com1->setMerchant('123456');
        $com1->setAmount(2);

        $item1 = new Item();
        $item1->setStamp('someStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr1')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setMerchant('111111')
            ->setCommission($com1)
            ->setReference('1-1')
            ->setUnits(1);

        $com2 = new Commission();
        $com2->setMerchant('123456');
        $com2->setAmount(5);

        $item2 = new Item();
        $item2->setStamp('someOtherStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr2')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setMerchant('222222')
            ->setReference('1-2')
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

        $this->assertEquals(true, $r->validate());
    }

    public function testShopInShopPaymentRequestFail()
    {
        $this->expectException(ValidationException::class);

        $r = new ShopInShopPaymentRequest();
        $r->setAmount(30);
        $r->setStamp('RequestStamp');
        $r->setReference('RequestReference123');
        $r->setCurrency('EUR');
        $r->setLanguage('EN');

        $item1 = new Item();
        $item1->setStamp('someStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr1')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setReference('1-1')
            ->setUnits(1);

        $com2 = new Commission();
        $com2->setMerchant('123456');
        $com2->setAmount(5);

        $item2 = new Item();
        $item2->setStamp('someOtherStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr2')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setMerchant('222222')
            ->setReference('1-2')
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

        $r->validate();
    }

    public static function shopInShopPaymentRequestItems()
    {
        return [
            'negative item failing validation' => [
                'itemsPrice'     => [20, -10],
                'amount'         => 10,
                'expectedResult' => false
            ],
            'positive validation'              => [
                'itemsPrice'     => [20, 10],
                'amount'         => 30,
                'expectedResult' => true
            ],
        ];
    }

    /**
     * @dataProvider shopInShopPaymentRequestItems
     */
    public function testNegativeRowsValidation($itemsPrice, $amount, $expectedResult)
    {
        $r = new ShopInShopPaymentRequest();
        $r->setAmount($amount);
        $r->setStamp('RequestStamp');
        $r->setReference('RequestReference123');
        $r->setCurrency('EUR');
        $r->setLanguage('EN');

        $i     = 0;
        $items = [];
        foreach ($itemsPrice as $price) {
            $com = new Commission();
            $com->setMerchant('123456');
            $com->setAmount(2);

            $item = new Item();
            $item->setStamp('someStamp' . $i)
                ->setDeliveryDate('12.12.2020')
                ->setProductCode('pr1' . $i)
                ->setVatPercentage(25)
                ->setUnitPrice($price)
                ->setUnits(1)
                ->setMerchant('222222')
                ->setReference('1-2')
                ->setCommission($com);

            $items[] = $item;
            $i++;
        }

        $r->setItems($items);

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
            $result = $r->validate();
        } catch (ValidationException $e) {
            $result = false;
        }

        $this->assertEquals($expectedResult, $result);
    }
}
