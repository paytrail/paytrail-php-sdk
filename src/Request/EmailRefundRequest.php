<?php

/**
 * Class EmailRefund
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;

/**
 * Class EmailRefund
 *
 * @see https://paytrail.github.io/api-documentation/#/examples?id=email-refund-request-body
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
     * Validates properties and throws an exception for invalid values
     *
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email.
     *
     * @param string|null $email
     *
     * @return EmailRefundRequest Return self to enable chaining.
     */
    public function setEmail(?string $email): RefundRequest
    {
        $this->email = $email;
        return $this;
    }
}
