<?php
/**
 * Class RefundResponse
 */

namespace OpMerchantServices\SDK\Response;

use OpMerchantServices\SDK\Interfaces\ResponseInterface;

/**
 * Class RefundResponse
 *
 * @package OpMerchantServices\SDK\Response
 */
class RefundResponse implements ResponseInterface
{

    /**
     * The provider id.
     *
     * @var string
     */
    protected $provider;

    /**
     * The refund status, "ok" for successful refunds.
     *
     * @var string
     */
    protected $status;

    /**
     * The payment transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Get the provider.
     *
     * @return string
     */
    public function getProvider(): string
    {

        return $this->provider;
    }

    /**
     * Set the provider.
     *
     * @param string $provider
     *
     * @return RefundResponse Return self to enable chaining.
     */
    public function setProvider(?string $provider): RefundResponse
    {

        $this->provider = $provider;

        return $this;
    }

    /**
     * Get the status.
     *
     * @return string
     */
    public function getStatus(): string
    {

        return $this->status;
    }

    /**
     * Set the status.
     *
     * @param string $status
     *
     * @return RefundResponse Return self to enable chaining.
     */
    public function setStatus(?string $status): RefundResponse
    {

        $this->status = $status;

        return $this;
    }

    /**
     * Get the transactionId.
     *
     * @return string
     */
    public function getTransactionId(): string
    {

        return $this->transactionId;
    }

    /**
     * Set the transactionId.
     *
     * @param string $transactionId
     *
     * @return RefundResponse Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId): RefundResponse
    {

        $this->transactionId = $transactionId;

        return $this;
    }
}
