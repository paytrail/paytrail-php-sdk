<?php

declare(strict_types=1);

namespace Tests\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\RefundItem;
use Paytrail\SDK\Request\EmailRefundRequest;
use PHPUnit\Framework\TestCase;

class EmailRefundRequestTest extends TestCase
{
    public function testEmailRefundRequestIsValid()
    {
        $emailRequest = new EmailRefundRequest();
        $emailRequest->setAmount(20);
        $emailRequest->setEmail('some@email.com');

        $item = new RefundItem();
        $item->setAmount(10)
            ->setStamp('someStamp');

        $item2 = new RefundItem();
        $item2->setAmount(10)
            ->setStamp('anotherStamp');

        $emailRequest->setItems([$item, $item2]);

        $cb = new CallbackUrl();
        $cb->setCancel('https://some.url.com/cancel')
            ->setSuccess('https://some.url.com/success');

        $emailRequest->setCallbackUrls($cb);

        $emailRequest->setRefundReference('ref-1234')
            ->setRefundStamp('c7557cd5d5f548daa5332ccc4abb264f');

        $this->assertTrue($emailRequest->validate());
    }

    public function testRefundRequestWithoutItemsAndAmountThrowsException()
    {
        $this->expectException(ValidationException::class);
        (new EmailRefundRequest())->validate();
    }

    public function testRefundWithItemsAndAmountMismatchThrowsException()
    {
        $this->expectException(ValidationException::class);
        (new EmailRefundRequest())->setAmount(100)
            ->setItems([
                (new RefundItem())->setAmount(200)
                    ->setStamp('foobar')
            ])->validate();
    }

    public function testRefundRequestWithoutCallbackUrlsThrowsError()
    {
        $this->expectException(ValidationException::class);
        (new EmailRefundRequest())->setAmount(100)
            ->validate();
    }
}
