<?php
/**
 * Class MitPaymentRequest
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Interfaces\TokenPaymentRequestInterface;

/**
 * Class MitPaymentRequest
 *
 * This class is used to create a MIT payment request object for
 * the CheckoutFinland\SDK\Client class.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=request4
 * @package OpMerchantServices\SDK\Request
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
