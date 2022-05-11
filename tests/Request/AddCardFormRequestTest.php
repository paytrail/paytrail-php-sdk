<?php
/**
 * Created by PhpStorm.
 * User: kimmok
 * Date: 03/04/2020
 * Time: 9.15
 */

namespace Tests\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Request\AddCardFormRequest;
use PHPUnit\Framework\TestCase;

class AddCardFormRequestTest extends TestCase
{
    public function validationProvider()
    {
        return [
            'checkout-account is empty' => [
                [
                    'checkoutAccount' => ''
                ],
                'checkout-account is empty'
            ],
            'checkout-algorithm is empty' => [
                [
                    'checkoutAccount' => 375917,
                    'checkoutAlgorithm' => ''
                ],
                'checkout-algorithm is empty'
            ],
            'Unsupported method chosen' => [
                [
                    'checkoutAccount' => 375917,
                    'checkoutAlgorithm' => 'sha256',
                    'checkoutMethod' => 'PAST'
                ],
                'Unsupported method chosen'
            ],
            'checkout-timestamp is empty' => [
                [
                    'checkoutAccount' => 375917,
                    'checkoutAlgorithm' => 'sha256',
                    'checkoutMethod' => 'POST',
                    'checkoutTimestamp' => ''
                ],
                'checkout-timestamp is empty'
            ],
            'checkout-redirect success url is empty' => [
                [
                    'checkoutAccount' => 375917,
                    'checkoutAlgorithm' => 'sha256',
                    'checkoutMethod' => 'POST',
                    'checkoutTimestamp' => '2020-04-07T08:20:13.729011Z',
                    'checkoutRedirectSuccessUrl' => ''
                ],
                'checkout-redirect success url is empty'
            ],
            'checkout-redirect cancel url is empty' => [
                [
                    'checkoutAccount' => 375917,
                    'checkoutAlgorithm' => 'sha256',
                    'checkoutMethod' => 'POST',
                    'checkoutTimestamp' => '2020-04-07T08:20:13.729011Z',
                    'checkoutRedirectSuccessUrl' => 'https://somedomain.com/success',
                    'checkoutRedirectCancelUrl' => ''
                ],
                'checkout-redirect cancel url is empty'
            ],
            'Unsupported language chosen' => [
                [
                    'checkoutAccount' => 375917,
                    'checkoutAlgorithm' => 'sha256',
                    'checkoutMethod' => 'POST',
                    'checkoutTimestamp' => '2020-04-07T08:20:13.729011Z',
                    'checkoutRedirectSuccessUrl' => 'https://somedomain.com/success',
                    'checkoutRedirectCancelUrl' => 'https://somedomain.com/cancel',
                    'language' => 'RU'
                ],
                'Unsupported language chosen'
            ]
        ];
    }

    public function testAddCardFormRequest()
    {
        $addCardFormRequest = new AddCardFormRequest();
        $addCardFormRequest->setCheckoutAccount(375917);
        $addCardFormRequest->setCheckoutAlgorithm('sha256');
        $addCardFormRequest->setCheckoutMethod('POST');
        $addCardFormRequest->setCheckoutNonce('15e8c3d6796f96');
        $addCardFormRequest->setCheckoutTimestamp('2020-04-07T08:20:13.729011Z');
        $addCardFormRequest->setCheckoutRedirectSuccessUrl('https://somedomain.com/success');
        $addCardFormRequest->setCheckoutRedirectCancelUrl('https://somedomain.com/cancel');
        $addCardFormRequest->setCheckoutCallbackSuccessUrl('https://someother.com/success');
        $addCardFormRequest->setCheckoutCallbackCancelUrl('https://someother.com/cancel');
        $addCardFormRequest->setLanguage('EN');

        $this->assertInstanceOf(AddCardFormRequest::class, $addCardFormRequest);

        $jsonData = $addCardFormRequest->jsonSerialize();

        $expectedArray = [
            'checkout-account' => 375917,
            'checkout-algorithm' => 'sha256',
            'checkout-method' => 'POST',
            'checkout-nonce' => '15e8c3d6796f96',
            'checkout-timestamp' => '2020-04-07T08:20:13.729011Z',
            'checkout-redirect-success-url' => 'https://somedomain.com/success',
            'checkout-redirect-cancel-url' => 'https://somedomain.com/cancel',
            'checkout-callback-success-url' => 'https://someother.com/success',
            'checkout-callback-cancel-url' => 'https://someother.com/cancel',
            'language' => 'EN'
        ];

        $this->assertEquals(true, $addCardFormRequest->validate());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($jsonData));
    }

    /**
     * @dataProvider validationProvider
     */
    public function testAddCardFormRequestValidationExceptionMessages($properties, $exceptionMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $addCardFormRequest = new AddCardFormRequest();

        foreach ($properties as $property => $value) {
            $this->setPrivateProperty($addCardFormRequest, $property, $value);
        }

        $addCardFormRequest->validate();
    }

    public function setPrivateProperty($class, $propertyName, $value)
    {
        $reflector = new \ReflectionClass($class);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }
}
