<?php
/**
 * Created by PhpStorm.
 * User: kimmok
 * Date: 03/04/2020
 * Time: 9.15
 */

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Request\AddCardFormRequest;
use PHPUnit\Framework\TestCase;

class AddCardFormRequestTest extends TestCase
{
    public function validationProvider()
    {
        return [
            'Paytrail account is empty' => [
                [
                    'paytrailAccount' => ''
                ],
                'Paytrail account is empty'
            ],
            'Paytrail algorithm is empty' => [
                [
                    'paytrailAccount' => 375917,
                    'paytrailAlgorithm' => ''
                ],
                'Paytrail algorithm is empty'
            ],
            'Unsupported method chosen' => [
                [
                    'paytrailAccount' => 375917,
                    'paytrailAlgorithm' => 'sha256',
                    'paytrailMethod' => 'PAST'
                ],
                'Unsupported method chosen'
            ],
            'Paytrail timestamp is empty' => [
                [
                    'paytrailAccount' => 375917,
                    'paytrailAlgorithm' => 'sha256',
                    'paytrailMethod' => 'POST',
                    'paytrailTimestamp' => ''
                ],
                'Paytrail timestamp is empty'
            ],
            'Paytrail redirect success url is empty' => [
                [
                    'paytrailAccount' => 375917,
                    'paytrailAlgorithm' => 'sha256',
                    'paytrailMethod' => 'POST',
                    'paytrailTimestamp' => '2020-04-07T08:20:13.729011Z',
                    'paytrailRedirectSuccessUrl' => ''
                ],
                'Paytrail redirect success url is empty'
            ],
            'Paytrail redirect cancel url is empty' => [
                [
                    'paytrailAccount' => 375917,
                    'paytrailAlgorithm' => 'sha256',
                    'paytrailMethod' => 'POST',
                    'paytrailTimestamp' => '2020-04-07T08:20:13.729011Z',
                    'paytrailRedirectSuccessUrl' => 'https://somedomain.com/success',
                    'paytrailRedirectCancelUrl' => ''
                ],
                'Paytrail redirect cancel url is empty'
            ],
            'Unsupported language chosen' => [
                [
                    'paytrailAccount' => 375917,
                    'paytrailAlgorithm' => 'sha256',
                    'paytrailMethod' => 'POST',
                    'paytrailTimestamp' => '2020-04-07T08:20:13.729011Z',
                    'paytrailRedirectSuccessUrl' => 'https://somedomain.com/success',
                    'paytrailRedirectCancelUrl' => 'https://somedomain.com/cancel',
                    'language' => 'RU'
                ],
                'Unsupported language chosen'
            ]
        ];
    }

    public function testAddCardFormRequest()
    {
        $addCardFormRequest = new AddCardFormRequest();
        $addCardFormRequest->setpaytrailAccount(375917);
        $addCardFormRequest->setpaytrailAlgorithm('sha256');
        $addCardFormRequest->setpaytrailMethod('POST');
        $addCardFormRequest->setpaytrailNonce('15e8c3d6796f96');
        $addCardFormRequest->setpaytrailTimestamp('2020-04-07T08:20:13.729011Z');
        $addCardFormRequest->setpaytrailRedirectSuccessUrl('https://somedomain.com/success');
        $addCardFormRequest->setpaytrailRedirectCancelUrl('https://somedomain.com/cancel');
        $addCardFormRequest->setpaytrailCallbackSuccessUrl('https://someother.com/success');
        $addCardFormRequest->setpaytrailCallbackCancelUrl('https://someother.com/cancel');
        $addCardFormRequest->setLanguage('EN');

        $this->assertInstanceOf(AddCardFormRequest::class, $addCardFormRequest);

        $jsonData = $addCardFormRequest->jsonSerialize();

        $expectedArray = [
            'paytrail-account' => 375917,
            'paytrail-algorithm' => 'sha256',
            'paytrail-method' => 'POST',
            'paytrail-nonce' => '15e8c3d6796f96',
            'paytrail-timestamp' => '2020-04-07T08:20:13.729011Z',
            'paytrail-redirect-success-url' => 'https://somedomain.com/success',
            'paytrail-redirect-cancel-url' => 'https://somedomain.com/cancel',
            'paytrail-callback-success-url' => 'https://someother.com/success',
            'paytrail-callback-cancel-url' => 'https://someother.com/cancel',
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
        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }
}
