<?php

/**
 * Class MitPaymentRequest
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Interfaces\TokenPaymentRequestInterface;

/**
 * Class MitPaymentRequest
 *
 * This class is used to create a MIT payment request object for
 * the Paytrail\SDK\Client class.
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=request4
 * @package Paytrail\SDK\Request
 */
class MitPaymentRequest extends AbstractPaymentRequest implements TokenPaymentRequestInterface
{
    /** @var string $token */
    protected $token;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['token'])) {
            throw new ValidationException('Token is empty');
        }

        return parent::validate();
    }

    public function setToken(string $token): TokenPaymentRequestInterface
    {
        $this->token = $token;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
