<?php

/**
 * Class GetTokenResponse
 */

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;
use Paytrail\SDK\Model\Token\Card;
use Paytrail\SDK\Model\Token\Customer;
use Paytrail\SDK\Util\ObjectPropertyConverter;

/**
 * Class GetTokenResponse
 *
 * @package Paytrail\SDK\Response
 */
class GetTokenResponse implements ResponseInterface, \JsonSerializable
{
    use ObjectPropertyConverter;

    /** @var string $token */
    protected $token;

    /** @var Card $card */
    protected $card;

    /** @var Customer $customer */
    protected $customer;

    /**
     * @param string $token
     * @return GetTokenResponse
     */
    public function setToken(string $token): GetTokenResponse
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param Card $card
     * @return GetTokenResponse
     */
    public function setCard($card): GetTokenResponse
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Customer $customer
     * @return GetTokenResponse
     */
    public function setCustomer($customer): GetTokenResponse
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
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

    public function loadFromStdClass(\stdClass $response): GetTokenResponse
    {
        $this->setToken($response->token);

        $card = new Card();
        $card->loadFromStdClass($response->card);
        $this->setCard($card);

        $customer = new Customer();
        $customer->loadFromStdClass($response->customer);
        $this->setCustomer($customer);

        return $this;
    }
}
