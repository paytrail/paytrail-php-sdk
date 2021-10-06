<?php
/**
 * Created by PhpStorm.
 * User: kimmok
 * Date: 14/04/2020
 * Time: 13.55
 */

use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Request\CitPaymentRequest;
use PHPUnit\Framework\TestCase;

class CitPaymentRequestTest extends TestCase
{
    public function testCitPaymentRequest()
    {
        $citPaymentRequest = new CitPaymentRequest();
        $citPaymentRequest->setAmount(30);
        $citPaymentRequest->setStamp('RequestStamp');
        $citPaymentRequest->setReference('RequestReference123');
        $citPaymentRequest->setCurrency('EUR');
        $citPaymentRequest->setLanguage('FI');
        $citPaymentRequest->setToken('c7441208-c2a1-4a10-8eb6-458bd8eaa64f');

        $item1 = new Item();
        $item1->setStamp('someStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr1')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setUnits(1);

        $item2 = new Item();
        $item2->setStamp('someOtherStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr2')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setUnits(2);

        $citPaymentRequest->setItems([$item1, $item2]);

        $customer = new Customer();
        $customer->setEmail('customer@email.com');

        $citPaymentRequest->setCustomer($customer);

        $callbackUrl = new CallbackUrl();
        $callbackUrl->setSuccess('https://somedomain.com/success')
            ->setCancel('https://somedomain.com/cancel');

        $citPaymentRequest->setCallbackUrls($callbackUrl);

        $redirectUrl = new CallbackUrl();
        $redirectUrl->setSuccess('https://someother.com/success')
            ->setCancel('https://someother.com/cancel');

        $citPaymentRequest->setRedirectUrls($redirectUrl);

        $this->assertEquals(true, $citPaymentRequest->validate());
    }
}
