<?php

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Model\PaymentMethodGroup;
use Paytrail\SDK\Model\Provider;
use Paytrail\SDK\Interfaces\ResponseInterface;

/**
 * Class PaymentResponse
 *
 * Represents a response object of payment creation.
 *
 * @package Paytrail\SDK\Response
 */
class PaymentResponse implements ResponseInterface
{
    /**
     * Assigned transaction ID for the payment.
     *
     * @var string|null
     */
    protected $transactionId;

    /**
     * URL to hosted payment gateway.
     *
     * @var string|null
     */
    protected $href;

    /**
     * Localized text with a link to the terms of payment.
     *
     * @var string|null
     */
    protected $terms;

    /**
     * Array of payment method group data with localized names and URLs to icons.
     *
     * @var PaymentMethodGroup[]
     */
    protected $groups = [];

    /**
     * The bank reference used for the payments.
     *
     * @var string|null
     */
    protected $reference;

    /**
     * Payment providers.
     *
     * @var Provider[]
     */
    protected $providers = [];

    /**
     * Get the transaction id.
     *
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string|null $transactionId
     *
     * @return PaymentResponse Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId): PaymentResponse
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get the href.
     *
     * @return string|null
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * Set the href.
     *
     * @param string|null $href
     *
     * @return PaymentResponse Return self to enable chaining.
     */
    public function setHref(?string $href): PaymentResponse
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTerms(): ?string
    {
        return $this->terms;
    }

    /**
     * @param string|null $terms
     * @return PaymentResponse
     */
    public function setTerms(?string $terms): PaymentResponse
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * @return PaymentMethodGroup[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param PaymentMethodGroup[]|array $groups
     * @return PaymentResponse
     */
    public function setGroups(array $groups): PaymentResponse
    {
        $this->groups = array_map(function ($group) {
            if (! $group instanceof PaymentMethodGroup) {
                return (new PaymentMethodGroup())->bindProperties($group);
            }

            return $group;
        }, $groups);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     * @return PaymentResponse
     */
    public function setReference(?string $reference): PaymentResponse
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get providers.
     *
     * @return Provider[]|null
     */
    public function getProviders(): ?array
    {
        return $this->providers;
    }

    /**
     * Set the providers.
     *
     * The parameter can be an array of Provider objects
     * or an array of stdClass instance. The latter will
     * be converted into provider class instances.
     *
     * @param Provider[]|array $providers The providers.
     *
     * @return PaymentResponse Return self to enable chaining.
     */
    public function setProviders(array $providers): PaymentResponse
    {
        $this->providers = array_map(function ($provider) {
            if (! $provider instanceof Provider) {
                return (new Provider())->bindProperties($provider);
            }

            return $provider;
        }, $providers);

        return $this;
    }
}
