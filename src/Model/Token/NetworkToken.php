<?php

/**
 * Class NetworkToken
 */

namespace Paytrail\SDK\Model\Token;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

/**
 * Class NetworkToken
 *
 * The NetworkToken class defiens additional details the network token.
 *
 * @package Paytrail\SDK\Model\Token
 */
class NetworkToken implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /** @var string $type */
    protected $type;

    /** @var string $partialPan */
    protected $partialPan;

    /** @var string $expireYear */
    protected $expireYear;

    /** @var string $expireMonth */
    protected $expireMonth;

    /** @var string $imageUrl */
    protected $imageUrl;

    /** @var string $paymentAccountReference */
    protected $paymentAccountReference;

    /**
     * Validate email
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToSnake();

        if (empty($props['type'])) {
            throw new ValidationException('Type is empty');
        }

        if (empty($props['partial_pan'])) {
            throw new ValidationException('Partial pan is empty');
        }

        if (empty($props['expire_year'])) {
            throw new ValidationException('Expire year is empty');
        }

        if (empty($props['expire_month'])) {
            throw new ValidationException('Expire month is empty');
        }

        return true;
    }

    /**
     * @param string $type
     * @return NetworkToken
     */
    public function setType(string $type): NetworkToken
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $partialPan
     * @return NetworkToken
     */
    public function setPartialPan(string $partialPan): NetworkToken
    {
        $this->partialPan = $partialPan;

        return $this;
    }

    /**
     * @return string
     */
    public function getPartialPan(): string
    {
        return $this->partialPan;
    }

    /**
     * @param string $expireYear
     * @return NetworkToken
     */
    public function setExpireYear(string $expireYear): NetworkToken
    {
        $this->expireYear = $expireYear;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpireYear(): string
    {
        return $this->expireYear;
    }

    /**
     * @param string $expireMonth
     * @return NetworkToken
     */
    public function setExpireMonth(string $expireMonth): NetworkToken
    {
        $this->expireMonth = $expireMonth;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpireMonth(): string
    {
        return $this->expireMonth;
    }

    /**
     * @param string|null $imageUrl
     * @return NetworkToken
     */
    public function setImageUrl(?string $imageUrl): NetworkToken
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $paymentAccountReference
     * @return NetworkToken
     */
    public function setPaymentAccountReference(?string $paymentAccountReference): NetworkToken
    {
        $this->paymentAccountReference = $paymentAccountReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentAccountReference(): ?string
    {
        return $this->paymentAccountReference;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     */
    public function jsonSerialize(): array
    {
        return array_filter($this->convertObjectVarsToSnake(), function ($item) {
            return $item !== null;
        });
    }

    /**
     * @param \stdClass $networkToken
     * @return NetworkToken
     */
    public function loadFromStdClass(\stdClass $networkToken): NetworkToken
    {
        $this->setType($networkToken->type);
        $this->setPartialPan($networkToken->partial_pan);
        $this->setExpireYear($networkToken->expire_year);
        $this->setExpireMonth($networkToken->expire_month);
        $this->setImageUrl($networkToken->image_url);
        $this->setPaymentAccountReference($networkToken->payment_account_reference ?? null);


        return $this;
    }
}
