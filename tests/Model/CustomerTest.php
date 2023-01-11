<?php

declare(strict_types=1);

namespace Tests\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testEmailValidity()
    {
        // Only email is mandatory
        $c = new Customer();
        $this->assertInstanceOf(Customer::class, $c);
        $c->setEmail('asdasdasd@sdaasddas.com');
        $this->assertIsBool($c->validate(), 'email is valid');
    }

    public function testExceptions()
    {
        // Test fail
        $this->expectException(ValidationException::class);
        $c2 = new Customer();
        $c2->setEmail('notAnEmailAddress');
        $c2->validate();
    }
}
