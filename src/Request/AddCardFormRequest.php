<?php

/**
 * Class AddCardFormRequest
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

/**
 * Class AddCardFormRequest
 *
 * @package Paytrail\SDK\Request
 */
class AddCardFormRequest implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /** @var int $checkoutAccount */
    protected $checkoutAccount;

    /** @var string $checkoutAlgorithm */
    protected $checkoutAlgorithm;

    /** @var string $checkoutMethod */
    protected $checkoutMethod;

    /** @var string $checkoutNonce */
    protected $checkoutNonce;

    /** @var string $checkoutTimestamp */
    protected $checkoutTimestamp;

    /** @var string $checkoutRedirectSuccessUrl */
    protected $checkoutRedirectSuccessUrl;

    /** @var string $checkoutRedirectCancelUrl */
    protected $checkoutRedirectCancelUrl;

    /** @var string $checkoutCallbackSuccessUrl */
    protected $checkoutCallbackSuccessUrl;

    /** @var string $checkoutCallbackCancelUrl */
    protected $checkoutCallbackCancelUrl;

    /** @var string $language */
    protected $language;

    /** @var string $signature */
    protected $signature;

    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToDashed();

        $supportedLanguages = ['FI', 'SV', 'EN'];
        $supportedMethods = ['GET', 'POST'];

        if (empty($props['checkout-account'])) {
            throw new ValidationException('checkout-account is empty');
        }

        if (empty($props['checkout-algorithm'])) {
            throw new ValidationException('checkout-algorithm is empty');
        }

        if (!in_array($props['checkout-method'], $supportedMethods)) {
            throw new ValidationException('Unsupported method chosen');
        }

        if (empty($props['checkout-timestamp'])) {
            throw new ValidationException('checkout-timestamp is empty');
        }

        if (empty($props['checkout-redirect-success-url'])) {
            throw new ValidationException('checkout-redirect success url is empty');
        }

        if (empty($props['checkout-redirect-cancel-url'])) {
            throw new ValidationException('checkout-redirect cancel url is empty');
        }

        if (!in_array($props['language'], $supportedLanguages)) {
            throw new ValidationException('Unsupported language chosen');
        }

        return true;
    }

    /**
     * @param int $checkoutAccount
     * @return AddCardFormRequest
     */
    public function setCheckoutAccount(int $checkoutAccount): AddCardFormRequest
    {
        $this->checkoutAccount = $checkoutAccount;

        return $this;
    }

    /**
     * @param string $checkoutAlgorithm
     * @return AddCardFormRequest
     */
    public function setCheckoutAlgorithm(string $checkoutAlgorithm): AddCardFormRequest
    {
        $this->checkoutAlgorithm = $checkoutAlgorithm;

        return $this;
    }

    /**
     * @param string $checkoutMethod
     * @return AddCardFormRequest
     */
    public function setCheckoutMethod(string $checkoutMethod): AddCardFormRequest
    {
        $this->checkoutMethod = $checkoutMethod;

        return $this;
    }

    /**
     * @param string $checkoutNonce
     * @return AddCardFormRequest
     */
    public function setCheckoutNonce(string $checkoutNonce): AddCardFormRequest
    {
        $this->checkoutNonce = $checkoutNonce;

        return $this;
    }

    public function setCheckoutTimestamp(string $checkoutTimestamp): AddCardFormRequest
    {
        $this->checkoutTimestamp = $checkoutTimestamp;

        return $this;
    }

    /**
     * @param string $checkoutRedirectSuccessUrl
     * @return AddCardFormRequest
     */
    public function setCheckoutRedirectSuccessUrl(string $checkoutRedirectSuccessUrl): AddCardFormRequest
    {
        $this->checkoutRedirectSuccessUrl = $checkoutRedirectSuccessUrl;

        return $this;
    }

    /**
     * @param string $checkoutRedirectCancelUrl
     * @return AddCardFormRequest
     */
    public function setCheckoutRedirectCancelUrl(string $checkoutRedirectCancelUrl): AddCardFormRequest
    {
        $this->checkoutRedirectCancelUrl = $checkoutRedirectCancelUrl;

        return $this;
    }

    /**
     * @param string $checkoutCallbackSuccessUrl
     * @return AddCardFormRequest
     */
    public function setCheckoutCallbackSuccessUrl(string $checkoutCallbackSuccessUrl): AddCardFormRequest
    {
        $this->checkoutCallbackSuccessUrl = $checkoutCallbackSuccessUrl;

        return $this;
    }

    /**
     * @param string $checkoutCallbackCancelUrl
     * @return AddCardFormRequest
     */
    public function setCheckoutCallbackCancelUrl(string $checkoutCallbackCancelUrl): AddCardFormRequest
    {
        $this->checkoutCallbackCancelUrl = $checkoutCallbackCancelUrl;

        return $this;
    }

    /**
     * @param string $language
     * @return AddCardFormRequest
     */
    public function setLanguage(string $language): AddCardFormRequest
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param string $signature
     * @return AddCardFormRequest
     */
    public function setSignature(string $signature): AddCardFormRequest
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_filter($this->convertObjectVarsToDashed(), function ($item) {
            return $item !== null;
        });
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $dataArray = json_encode($this->jsonSerialize());
        return json_decode($dataArray, true);
    }
}
