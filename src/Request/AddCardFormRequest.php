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

    /** @var int $paytrailAccount */
    protected $paytrailAccount;

    /** @var string $paytrailAlgorithm */
    protected $paytrailAlgorithm;

    /** @var string $paytrailMethod */
    protected $paytrailMethod;

    /** @var string $paytrailNonce */
    protected $paytrailNonce;

    /** @var string $paytrailTimestamp */
    protected $paytrailTimestamp;

    /** @var string $paytrailRedirectSuccessUrl */
    protected $paytrailRedirectSuccessUrl;

    /** @var string $paytrailRedirectCancelUrl */
    protected $paytrailRedirectCancelUrl;

    /** @var string $paytrailCallbackSuccessUrl */
    protected $paytrailCallbackSuccessUrl;

    /** @var string $paytrailCallbackCancelUrl */
    protected $paytrailCallbackCancelUrl;

    /** @var string $language */
    protected $language;

    /** @var string $signature */
    protected $signature;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToDashed();

        $supportedLanguages = ['FI', 'SV', 'EN'];
        $supportedMethods = ['GET', 'POST'];

        if (empty($props['paytrail-account'])) {
            throw new ValidationException('Paytrail account is empty');
        }

        if (empty($props['paytrail-algorithm'])) {
            throw new ValidationException('Paytrail algorithm is empty');
        }

        if (!in_array($props['paytrail-method'], $supportedMethods)) {
            throw new ValidationException('Unsupported method chosen');
        }

        if (empty($props['paytrail-timestamp'])) {
            throw new ValidationException('Paytrail timestamp is empty');
        }

        if (empty($props['paytrail-redirect-success-url'])) {
            throw new ValidationException('Paytrail redirect success url is empty');
        }

        if (empty($props['paytrail-redirect-cancel-url'])) {
            throw new ValidationException('Paytrail redirect cancel url is empty');
        }

        if (!in_array($props['language'], $supportedLanguages)) {
            throw new ValidationException('Unsupported language chosen');
        }

        return true;
    }

    /**
     * @param int $paytrailAccount
     * @return AddCardFormRequest
     */
    public function setpaytrailAccount(int $paytrailAccount): AddCardFormRequest
    {
        $this->paytrailAccount = $paytrailAccount;

        return $this;
    }

    /**
     * @param string $paytrailAlgorithm
     * @return AddCardFormRequest
     */
    public function setpaytrailAlgorithm(string $paytrailAlgorithm): AddCardFormRequest
    {
        $this->paytrailAlgorithm = $paytrailAlgorithm;

        return $this;
    }

    /**
     * @param string $paytrailMethod
     * @return AddCardFormRequest
     */
    public function setpaytrailMethod(string $paytrailMethod): AddCardFormRequest
    {
        $this->paytrailMethod = $paytrailMethod;

        return $this;
    }

    /**
     * @param string $paytrailNonce
     * @return AddCardFormRequest
     */
    public function setpaytrailNonce(string $paytrailNonce): AddCardFormRequest
    {
        $this->paytrailNonce = $paytrailNonce;

        return $this;
    }

    public function setpaytrailTimestamp(string $paytrailTimestamp): AddCardFormRequest
    {
        $this->paytrailTimestamp = $paytrailTimestamp;

        return $this;
    }

    /**
     * @param string $paytrailRedirectSuccessUrl
     * @return AddCardFormRequest
     */
    public function setpaytrailRedirectSuccessUrl(string $paytrailRedirectSuccessUrl): AddCardFormRequest
    {
        $this->paytrailRedirectSuccessUrl = $paytrailRedirectSuccessUrl;

        return $this;
    }

    /**
     * @param string $paytrailRedirectCancelUrl
     * @return AddCardFormRequest
     */
    public function setpaytrailRedirectCancelUrl(string $paytrailRedirectCancelUrl): AddCardFormRequest
    {
        $this->paytrailRedirectCancelUrl = $paytrailRedirectCancelUrl;

        return $this;
    }

    /**
     * @param string $paytrailCallbackSuccessUrl
     * @return AddCardFormRequest
     */
    public function setpaytrailCallbackSuccessUrl(string $paytrailCallbackSuccessUrl): AddCardFormRequest
    {
        $this->paytrailCallbackSuccessUrl = $paytrailCallbackSuccessUrl;

        return $this;
    }

    /**
     * @param string $paytrailCallbackCancelUrl
     * @return AddCardFormRequest
     */
    public function setpaytrailCallbackCancelUrl(string $paytrailCallbackCancelUrl): AddCardFormRequest
    {
        $this->paytrailCallbackCancelUrl = $paytrailCallbackCancelUrl;

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
    public function jsonSerialize()
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