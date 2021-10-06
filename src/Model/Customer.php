<?php
/**
 * Class CustomerInterface
 */

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Interfaces\CustomerInterface;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class CustomerInterface
 *
 * The customer class defines the customer details object.
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=customer
 * @package Paytrail\SDK\Model
 */
class Customer implements \JsonSerializable, CustomerInterface
{

    use JsonSerializable;

    /**
     * Validate email
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['email'])) {
            throw new ValidationException('Email is empty');
        }

        if (filter_var($props['email'], FILTER_VALIDATE_EMAIL) === false) {
            throw new ValidationException('Email is not a valid email address');
        }

        return true;
    }

    /**
     * The customer email.
     *
     * @var string
     */
    protected $email;

    /**
     * The customer first name.
     *
     * @var string
     */
    protected $firstName;

    /**
     * The customer last name.
     *
     * @var string
     */
    protected $lastName;

    /**
     * The customer phone.
     *
     * @var string
     */
    protected $phone;

    /**
     * The customer VAT id.
     *
     * @var string
     */
    protected $vatId;

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): ?string
    {

        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return self Return self to enable chaining.
     */
    public function setEmail(?string $email) : CustomerInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): ?string
    {

        return $this->firstName;
    }

    /**
     * Set first name.
     *
     * @param string $firstName
     *
     * @return self Return self to enable chaining.
     */
    public function setFirstName(?string $firstName) : CustomerInterface
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName(): ?string
    {

        return $this->lastName;
    }

    /**
     * Set last name.
     *
     * @param string $lastName
     *
     * @return self Return self to enable chaining.
     */
    public function setLastName(?string $lastName) : CustomerInterface
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): ?string
    {

        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return self Return self to enable chaining.
     */
    public function setPhone(?string $phone) : CustomerInterface
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get VAT id.
     *
     * @return string
     */
    public function getVatId(): ?string
    {

        return $this->vatId;
    }

    /**
     * Set VAT id.
     *
     * @param string $vatId
     *
     * @return self Return self to enable chaining.
     */
    public function setVatId(?string $vatId) : CustomerInterface
    {
        $this->vatId = $vatId;

        return $this;
    }
}
