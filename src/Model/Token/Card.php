<?php
/**
 * Class Card
 */

namespace OpMerchantServices\SDK\Model\Token;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Util\ObjectPropertyConverter;

/**
 * Class Card
 *
 * The Card class defines the card details object.
 *
 * @package OpMerchantServices\SDK\Model\Token
 */
class Card implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /** @var string $type */
    protected $type;

    /** @var string $bin */
    protected $bin;

    /** @var string $partialPan */
    protected $partialPan;

    /** @var string $expireYear */
    protected $expireYear;

    /** @var string $expireMonth */
    protected $expireMonth;

    /** @var string $cvcRequired */
    protected $cvcRequired;

    /** @var string $funding */
    protected $funding;

    /** @var string $category */
    protected $category;

    /** @var string $countryCode */
    protected $countryCode;

    /** @var string $panFingerprint */
    protected $panFingerprint;

    /** @var string $cardFingerprint */
    protected $cardFingerprint;

    /**
     * Validate email
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToSnake();

        $cvcRequiredOptions = ['yes', 'no', 'not_tested'];
        $fundingOptions = ['credit', 'debit', 'unknown'];
        $categoryOptions = ['business', 'prepaid', 'unknown'];

        if (empty($props['type'])) {
            throw new ValidationException('Type is empty');
        }

        if (empty($props['bin'])) {
            throw new ValidationException('Bin is empty');
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

        if (!in_array($props['cvc_required'], $cvcRequiredOptions)) {
            throw new ValidationException('Unsupported CVC required option given');
        }

        if (!in_array($props['funding'], $fundingOptions)) {
            throw new ValidationException('Unsupported funding option given');
        }

        if (!in_array($props['category'], $categoryOptions)) {
            throw new ValidationException('Unsupported category option given');
        }

        if (empty($props['country_code'])) {
            throw new ValidationException('Country code is empty');
        }

        if (empty($props['pan_fingerprint'])) {
            throw new ValidationException('Pan fingerprint is empty');
        }

        if (empty($props['card_fingerprint'])) {
            throw new ValidationException('Card fingerprint is empty');
        }

        return true;
    }

    /**
     * @param string $type
     * @return Card
     */
    public function setType(string $type): Card
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
     * @param string $bin
     * @return Card
     */
    public function setBin(string $bin): Card
    {
        $this->bin = $bin;

        return $this;
    }

    /**
     * @return string
     */
    public function getBin(): string
    {
        return $this->bin;
    }

    /**
     * @param string $partialPan
     * @return Card
     */
    public function setPartialPan(string $partialPan): Card
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
     * @return Card
     */
    public function setExpireYear(string $expireYear): Card
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
     * @return Card
     */
    public function setExpireMonth(string $expireMonth): Card
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
     * @param string $cvcRequired
     * @return Card
     */
    public function setCvcRequired(string $cvcRequired): Card
    {
        $this->cvcRequired = $cvcRequired;

        return $this;
    }

    /**
     * @return string
     */
    public function getCvcRequired(): string
    {
        return $this->cvcRequired;
    }

    /**
     * @param string $funding
     * @return Card
     */
    public function setFunding(string $funding): Card
    {
        $this->funding = $funding;

        return $this;
    }

    /**
     * @return string
     */
    public function getFunding(): string
    {
        return $this->funding;
    }

    /**
     * @param string $category
     * @return Card
     */
    public function setCategory(string $category): Card
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $countryCode
     * @return Card
     */
    public function setCountryCode(string $countryCode): Card
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $panFingerprint
     * @return Card
     */
    public function setPanFingerprint(string $panFingerprint): Card
    {
        $this->panFingerprint = $panFingerprint;

        return $this;
    }

    /**
     * @return string
     */
    public function getPanFingerprint(): string
    {
        return $this->panFingerprint;
    }

    /**
     * @param string $cardFingerprint
     * @return Card
     */
    public function setCardFingerprint(string $cardFingerprint): Card
    {
        $this->cardFingerprint = $cardFingerprint;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardFingerprint(): string
    {
        return $this->cardFingerprint;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter($this->convertObjectVarsToSnake(), function ($item) {
            return $item !== null;
        });
    }

    /**
     * @param \stdClass $card
     * @return Card
     */
    public function loadFromStdClass(\stdClass $card): Card
    {
        $this->setType($card->type);
        $this->setBin($card->bin);
        $this->setPartialPan($card->partial_pan);
        $this->setExpireYear($card->expire_year);
        $this->setExpireMonth($card->expire_month);
        $this->setCvcRequired($card->cvc_required);
        $this->setFunding($card->funding);
        $this->setCategory($card->category);
        $this->setCountryCode($card->country_code);
        $this->setPanFingerprint($card->pan_fingerprint);
        $this->setCardFingerprint($card->card_fingerprint);

        return $this;
    }
}
