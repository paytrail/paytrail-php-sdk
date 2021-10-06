<?php
/**
 * Class PaymentResponse
 */

namespace OpMerchantServices\SDK\Response;

use OpMerchantServices\SDK\Interfaces\ResponseInterface;

/**
 * Class PaymentStatusResponse
 *
 * Represents a response object of payment status.
 *
 * @package OpMerchantServices\SDK\Response
 */
class PaymentStatusResponse implements ResponseInterface
{
    /**
     * The transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Payment status. Possible values: new, ok, fail, pending,
     * or delayed
     *
     * @var string
     */
    protected $status;

    /**
     * Total amount of the payment in currency's minor units,
     * eg. for Euros means cents.
     *
     * @var integer
     */
    protected $amount;

    /**
     * Currency
     *
     * @var string
     */
    protected $currency;

    /**
     * Merchant unique identifier for the order.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Order reference
     *
     * @var string
     */
    protected $reference;

    /**
     * Transaction creation timestamp
     *
     * @var string
     */
    protected $createdAt;

    /**
     * Payment API url.
     *
     * @var string
     */
    protected $href;

    /**
     * If processed, the name of the payment method provider
     *
     * @var string
     */
    protected $provider;

    /**
     * If paid, the filing code issued by the payment method
     * provider if any. Some providers do not return the filing
     * code.
     *
     * @var string
     */
    protected $filingCode;

    /**
     * Timestamp when the transaction was paid
     *
     * @var string
     */
    protected $paidAt;


    /**
     * Get the transaction id.
     *
     * @return string
     */ 
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setTransactionId(string $transactionId): PaymentStatusResponse
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get the payment status.
     *
     * @return string
     */ 
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the payment status.
     *
     * @param string $status Possible values: new, ok, fail,
     * pending, or delayed
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setStatus(?string $status): PaymentStatusResponse
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the total amount of the payment.
     *
     * @return integer
     */ 
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * Set the total amount of the payment in currency's minor units,
     * eg. for Euros means cents.
     *
     * @param integer $amount
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setAmount(?int $amount): PaymentStatusResponse
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */ 
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setCurrency(?string $currency): PaymentStatusResponse
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get merchant unique identifier for the order.
     *
     * @return string
     */ 
    public function getStamp(): ?string
    {
        return $this->stamp;
    }

    /**
     * Set merchant unique identifier for the order.
     *
     * @param string $stamp
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setStamp(?string $stamp): PaymentStatusResponse
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get the order reference.
     *
     * @return string
     */ 
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the order reference.
     *
     * @param string $reference
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setReference(?string $reference): PaymentStatusResponse
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the transaction creation timestamp.
     *
     * @return string
     */ 
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * Set the transaction creation timestamp.
     *
     * @param string $createdAt
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setCreatedAt(?string $createdAt): PaymentStatusResponse
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get payment API url.
     *
     * @return string
     */ 
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * Set payment API url.
     *
     * @param string $href
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setHref(?string $href): PaymentStatusResponse
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get provider name.
     *
     * @return string
     */ 
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set provider name.
     *
     * @param string $provider If processed, the name of the payment method provider
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setProvider(?string $provider): PaymentStatusResponse
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get filing code.
     *
     * @return string
     */ 
    public function getFilingCode(): ?string
    {
        return $this->filingCode;
    }

    /**
     * Set filing code.
     *
     * @param string $filingCode
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setFilingCode(?string $filingCode): PaymentStatusResponse
    {
        $this->filingCode = $filingCode;

        return $this;
    }

    /**
     * Get timestamp when the transaction was paid
     *
     * @return string
     */ 
    public function getPaidAt(): ?string
    {
        return $this->paidAt;
    }

    /**
     * Set timestamp when the transaction was paid
     *
     * @param string $paidAt
     *
     * @return PaymentStatusResponse Return self to enable chaining.
     */ 
    public function setPaidAt(?string $paidAt): PaymentStatusResponse
    {
        $this->paidAt = $paidAt;

        return $this;
    }
}
