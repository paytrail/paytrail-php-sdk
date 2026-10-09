<?php

declare(strict_types=1);

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class ProviderDetails
 *
 * Payment method specific parameters for the payment request.
 *
 * @see https://docs.paytrail.com/#/?id=providerdetails
 * @package Paytrail\SDK\Model
 */
class ProviderDetails implements \JsonSerializable
{
    use JsonSerializable;

    /**
     * Klarna parameters for conversion boosters.
     *
     * @var KlarnaProviderDetails
     */
    protected $klarna;

    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        if ($this->klarna !== null) {
            $this->klarna->validate();
        }

        return true;
    }

    /**
     * @return KlarnaProviderDetails|null
     */
    public function getKlarna(): ?KlarnaProviderDetails
    {
        return $this->klarna;
    }

    /**
     * @param KlarnaProviderDetails|null $klarna
     * @return ProviderDetails Return self to enable chaining.
     */
    public function setKlarna(?KlarnaProviderDetails $klarna): ProviderDetails
    {
        $this->klarna = $klarna;

        return $this;
    }
}
