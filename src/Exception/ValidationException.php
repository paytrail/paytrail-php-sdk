<?php
/**
 * Class ValidationException
 */

namespace OpMerchantServices\SDK\Exception;


/**
 * Class ValidationException
 * This exception is a simple wrapper for
 * a Respect\Validation NestedValidationException instance.
 *
 * This exception holds the general expection message
 *
 * @package OpMerchantServices\SDK\Exception
 */
class ValidationException extends \Exception
{

    /**
     * Holds the previous NestedValidationException instance.
     *
     * $var NestedValidationException
     */
    protected $previous;

    /**
     * Holds all error messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * Set multiple messages.
     *
     * @param array $messages The error messages.
     * @return ValidationException Return self to enable chaining.
     */
    public function setMessages(array $messages = []) : ValidationException
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the messages.
     *
     * @return array
     */
    public function getMessages() : array
    {
        return $this->messages;
    }
}
