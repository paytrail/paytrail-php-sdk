<?php

declare(strict_types=1);

namespace Tests\Model;

use Exception;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Request\AbstractPaymentRequest;
use ReflectionProperty;

class AbstractPaymentRequestTest extends MockeryTestCase
{
    public function testValidationExceptionMessages(): void
    {
        $paymentRequest = $this->getMockForAbstractClass(AbstractPaymentRequest::class);

        try {
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Amount is empty', $e->getMessage());
        }

        try {
            $this->setProtectedProperty($paymentRequest, 'amount', 'ten');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Amount is not a number', $e->getMessage());
        }

        try {
            $paymentRequest->setAmount(10);
            $this->setProtectedProperty($paymentRequest, 'items', 'string');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Items needs to be type of array', $e->getMessage());
        }

        try {
            $paymentRequest->setAmount(10);
            $paymentRequest->setItems($this->getPaymentItems());
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Amount doesnt match ItemsTotal', $e->getMessage());
        }

        try {
            $paymentRequest->setItems(null);
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Stamp is empty', $e->getMessage());
        }

        try {
            $paymentRequest->setStamp('stamp');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Reference is empty', $e->getMessage());
        }

        try {
            $paymentRequest->setReference('reference');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Currency is empty', $e->getMessage());
        }

        try {
            $paymentRequest->setCurrency('USD');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Unsupported currency chosen', $e->getMessage());
        }

        try {
            $paymentRequest->setCurrency('EUR');
            $paymentRequest->setLanguage('UN');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Unsupported language chosen', $e->getMessage());
        }

        try {
            $paymentRequest->setLanguage('EN');
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('Customer is empty', $e->getMessage());
        }

        try {
            $paymentRequest->setCustomer(new Customer());
            $paymentRequest->validate();
        } catch (Exception $e) {
            $this->assertEquals('RedirectUrls is empty', $e->getMessage());
        }
    }

    private function setProtectedProperty(
        AbstractPaymentRequest $paymentRequest,
        string $propertyName,
        string $value
    ): void {
        $attribute = new ReflectionProperty($paymentRequest, $propertyName);
        if (PHP_VERSION_ID < 80100) {
            $attribute->setAccessible(true);
        }
        $attribute->setValue($paymentRequest, $value);
    }

    private function getPaymentItems(): array
    {
        return [
            (new Item())
                ->setStamp('someStamp')
                ->setDeliveryDate('12.12.2020')
                ->setProductCode('pr1')
                ->setVatPercentage(25)
                ->setUnitPrice(10)
                ->setUnits(1),
            (new Item())
                ->setStamp('someOtherStamp')
                ->setDeliveryDate('12.12.2020')
                ->setProductCode('pr2')
                ->setVatPercentage(25)
                ->setUnitPrice(10)
                ->setUnits(2),
        ];
    }
}
