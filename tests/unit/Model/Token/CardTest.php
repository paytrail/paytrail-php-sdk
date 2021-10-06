<?php

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Token\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function validationProvider()
    {
        return [
            'Type is empty' => [
                [
                    'type' => ''
                ],
                'Type is empty'
            ],
            'Bin is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => ''
                ],
                'Bin is empty'
            ],
            'Partial pan is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => ''
                ],
                'Partial pan is empty'
            ],
            'Expire year is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => ''
                ],
                'Expire year is empty'
            ],
            'Expire month is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => ''
                ],
                'Expire month is empty'
            ],
            'Unsupported CVC required option given' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => '11',
                    'cvcRequired' => 'nothing'
                ],
                'Unsupported CVC required option given'
            ],
            'Unsupported funding option given' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => '11',
                    'cvcRequired' => 'no',
                    'funding' => 'cash'
                ],
                'Unsupported funding option given'
            ],
            'Unsupported category option given' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => '11',
                    'cvcRequired' => 'no',
                    'funding' => 'debit',
                    'category' => 'tokenizied'
                ],
                'Unsupported category option given'
            ],
            'Country code is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => '11',
                    'cvcRequired' => 'no',
                    'funding' => 'debit',
                    'category' => 'unknown',
                    'countryCode' => ''
                ],
                'Country code is empty'
            ],
            'Pan fingerprint is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => '11',
                    'cvcRequired' => 'no',
                    'funding' => 'debit',
                    'category' => 'unknown',
                    'countryCode' => 'FI',
                    'panFingerprint' => ''
                ],
                'Pan fingerprint is empty'
            ],
            'Card fingerprint is empty' => [
                [
                    'type' => 'Visa',
                    'bin' => '415301',
                    'partialPan' => '0024',
                    'expireYear' => '2023',
                    'expireMonth' => '11',
                    'cvcRequired' => 'no',
                    'funding' => 'debit',
                    'category' => 'unknown',
                    'countryCode' => 'FI',
                    'panFingerprint' => '693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce',
                    'cardFingerprint' => '',
                ],
                'Card fingerprint is empty'
            ]
        ];
    }

    public function testCardValidity()
    {
        $card = new Card();
        $card->setType('Visa');
        $card->setBin('415301');
        $card->setPartialPan('0024');
        $card->setExpireYear('2023');
        $card->setExpireMonth('11');
        $card->setCvcRequired('no');
        $card->setFunding('debit');
        $card->setCategory('unknown');
        $card->setCountryCode('FI');
        $card->setPanFingerprint('693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce');
        $card->setCardFingerprint('c34cdd1952deb81734c012fbb11eabc56c4d61d198f28b448327ccf13f45417f');

        $this->assertInstanceOf(Card::class, $card);

        $jsonData = $card->jsonSerialize();

        $expectedArray = [
            'type' => 'Visa',
            'bin' => '415301',
            'partial_pan' => '0024',
            'expire_year' => '2023',
            'expire_month' => '11',
            'cvc_required' => 'no',
            'funding' => 'debit',
            'category' => 'unknown',
            'country_code' => 'FI',
            'pan_fingerprint' => '693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce',
            'card_fingerprint' => 'c34cdd1952deb81734c012fbb11eabc56c4d61d198f28b448327ccf13f45417f'
        ];

        $this->assertEquals(true, $card->validate());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($jsonData));
    }

    /**
     * @dataProvider validationProvider
     */
    public function testCardValidationExceptionMessages($properties, $exceptionMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $card = new Card();

        foreach ($properties as $property => $value) {
            $this->setPrivateProperty($card, $property, $value);
        }

        $card->validate();
    }

    public function setPrivateProperty($class, $propertyName, $value)
    {
        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }
}
