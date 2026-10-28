<?php

declare(strict_types=1);

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class KlarnaProviderDetails
 *
 * Klarna specific parameters passed in providerDetails.klarna.
 *
 * @see https://docs.paytrail.com/#/?id=klarna-provider-details
 * @package Paytrail\SDK\Model
 */
class KlarnaProviderDetails implements \JsonSerializable
{
    use JsonSerializable;

    /**
     * Klarna Network Session Token from the Klarna Web SDK.
     *
     * @var string
     */
    protected $networkSessionToken;

    /**
     * Serialized JSON with enriched data to improve approval rates.
     *
     * @var string
     */
    protected $networkData;

    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        if ($this->networkSessionToken !== null && mb_strlen($this->networkSessionToken) > 8192) {
            throw new ValidationException('Klarna networkSessionToken exceeds 8192 characters');
        }

        if ($this->networkData !== null && mb_strlen($this->networkData) > 10240) {
            throw new ValidationException('Klarna networkData exceeds 10240 characters');
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function getNetworkSessionToken(): ?string
    {
        return $this->networkSessionToken;
    }

    /**
     * @param string|null $networkSessionToken
     * @return KlarnaProviderDetails Return self to enable chaining.
     */
    public function setNetworkSessionToken(?string $networkSessionToken): KlarnaProviderDetails
    {
        $this->networkSessionToken = $networkSessionToken;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkData(): ?string
    {
        return $this->networkData;
    }

    /**
     * @param string|null $networkData Serialized JSON.
     * @return KlarnaProviderDetails Return self to enable chaining.
     */
    public function setNetworkData(?string $networkData): KlarnaProviderDetails
    {
        $this->networkData = $networkData;

        return $this;
    }
}
