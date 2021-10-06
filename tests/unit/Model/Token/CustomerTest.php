<?php

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Token\Customer;
use PHPUnit\Framework\TestCase;

class TokenCustomerTest extends TestCase
{
    public function validationProvider()
    {
        return [
            'Network address is empty' => [['networkAddress' => ''], 'Network address is empty'],
            'Country code is empty' => [['networkAddress' => '93.174.192.154', 'countryCode' => ''], 'Country code is empty']
        ];
    }

    public function testTokenCustomer()
    {
        $customer = new Customer();
        $customer->setNetworkAddress('93.174.192.154');
        $customer->setCountryCode('FI');

        $this->assertInstanceOf(Customer::class, $customer);

        $jsonData = $customer->jsonSerialize();

        $expectedArray = [
            'network_address' => '93.174.192.154',
            'country_code' => 'FI'
        ];

        $this->assertEquals(true, $customer->validate());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($jsonData));
    }

    /**
     * @dataProvider validationProvider
     */
    public function testCustomerValidationExceptionMessages($properties, $exceptionMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $customer = new Customer();

        foreach ($properties as $property => $value) {
            $this->setPrivateProperty($customer, $property, $value);
        }

        $customer->validate();
    }

    public function setPrivateProperty($class, $propertyName, $value)
    {
        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }
}
