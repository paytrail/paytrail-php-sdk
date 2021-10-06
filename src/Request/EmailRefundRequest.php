<?php
/**
 * Class EmailRefund
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\RefundItem;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class EmailRefund
 *
 * @see https://Paytrail.github.io/psp-api/#/examples?id=email-refund-request-body
 *
 * @package Paytrail\SDK\Request
 */
class EmailRefundRequest extends RefundRequest
{

    /**
     * Email to which the refund message will be sent.
     *
     * @var string
     */
    protected $email;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the validate() fails.
     * @throws ValidationException
     */
    public function validate()
    {
        parent::validate();

        if (empty($this->email)) {
            throw new ValidationException('email can not be empty');
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new ValidationException('email is not a valid email address');
        }

        return true;
    }

    /**
     * Get the email.
     *
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Set the email.
     *
     * @param string $email
     *
     * @return EmailRefundRequest Return self to enable chaining.
     */
    public function setEmail(?string $email) : EmailRefundRequest
    {
        $this->email = $email;

        return $this;
    }
}
