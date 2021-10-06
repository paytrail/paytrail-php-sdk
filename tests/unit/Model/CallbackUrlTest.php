<?php


use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\CallbackUrl;
use PHPUnit\Framework\TestCase;

class CallbackUrlTest extends TestCase
{
    public function testExceptions()
    {
        $this->expectException(ValidationException::class);
        $a = new CallbackUrl;
        $a->validate();
    }

    public function testIsCallbackUrlValid()
    {
        $c = new CallbackUrl;

        $this->assertInstanceOf(CallbackUrl::class, $c);

        $c->setSuccess('https://someurl.com/success')
            ->setCancel('https://someurl.com/cancel');

        try {
            $this->assertIsBool($c->validate(), 'CallbackUrl::validate is bool');
        } catch (ValidationException $e) {

        }

    }

    public function testExceptionMessages()
    {
        $c = new CallbackUrl;

        try {
            $c->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('Success is empty', $e->getMessage());
        }

        $c->setSuccess('someString');

        try {
            $c->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('Cancel is empty', $e->getMessage());
        }

        $c->setCancel('someOtherString');

        try {
            $this->assertIsBool($c->validate(), 'CallbackUrl::validate is bool');
        } catch (ValidationException $e) {
        }


    }

}