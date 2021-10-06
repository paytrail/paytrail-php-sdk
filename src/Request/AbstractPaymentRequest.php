<?php
/**
 * Class PaymentRequest
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Interfaces\AddressInterface;
use OpMerchantServices\SDK\Interfaces\CallbackUrlInterface;
use OpMerchantServices\SDK\Interfaces\CustomerInterface;
use OpMerchantServices\SDK\Interfaces\ItemInterface;
use OpMerchantServices\SDK\Interfaces\PaymentRequestInterface;
use OpMerchantServices\SDK\Model\Address;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Util\JsonSerializable;

/**
 * Class AbstractPaymentRequest
 *
 * This class is used to create a payment request object for
 * the CheckoutFinland\SDK\Client class.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=create-request-body
 * @package OpMerchantServices\SDK\Request
 */
abstract class AbstractPaymentRequest implements \JsonSerializable, PaymentRequestInterface
{

    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        $supportedLanguages = ['FI', 'SV', 'EN'];
         $supportedCurrencies = ['EUR'];

        if (empty($props['items'])) {
            throw new ValidationException('Items is empty');
        }

        if (!is_array($props['items'])) {
            throw new ValidationException('Items needs to be type of array');
        }

        // Count the total amount of the payment.
        $items_total = array_reduce($this->getItems(), function ($carry = 0, ?Item $item = null) {
            if ($item === null) {
                return $carry;
            }
            return $item->getUnitPrice() * $item->getUnits() + $carry;
        });

        if (empty($props['amount'])) {
            throw new ValidationException('Amount is empty');
        }

        if (filter_var($props['amount'], FILTER_VALIDATE_INT) === false) {
            throw new ValidationException('Amount is not a number');
        }

        if ($props['amount'] !== $items_total) {
            throw new ValidationException('Amount doesnt match ItemsTotal');
        }

        if (empty($props['stamp'])) {
            throw new ValidationException('Stamp is empty');
        }

        if (empty($props['reference'])) {
            throw new ValidationException('Reference is empty');
        }

        if (empty($props['currency'])) {
            throw new ValidationException('Currency is empty');
        }

        if (!in_array($props['currency'], $supportedCurrencies)) {
            throw new ValidationException('Unsupported currency chosen');
        }

        if (!in_array($props['language'], $supportedLanguages)) {
            throw new ValidationException('Unsupported language chosen');
        }

        if (empty($props['customer'])) {
            throw new ValidationException('Customer is empty');
        }

        if (empty($props['redirectUrls'])) {
            throw new ValidationException('RedirectUrls is empty');
        }

        // Validate the items.
        array_walk($this->items, function (Item $item) {
            $item->validate();
        });

        // Validate the customer.
        $this->customer->validate();

        // Validate the address values.
        if (! empty($this->deliveryAddress)) {
            $this->deliveryAddress->validate();
        }
        if (! empty($this->invoicingAddress)) {
            $this->invoicingAddress->validate();
        }

        return true;
    }

    /**
     * Merchant unique identifier for the order.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Order reference.
     *
     * @var string
     */
    protected $reference;

    /**
     * Total amount of the payment in currency's minor units,
     * eg. for Euros use cents. Must match the total sum of items.
     *
     * @var integer
     */
    protected $amount;

    /**
     * Currency, only EUR supported at the moment.
     *
     * @var string
     */
    protected $currency;

    /**
     * Payment's language, currently supported are FI, SV, and EN
     *
     * @var string
     */
    protected $language;

    /**
     * Array of items.
     *
     * @var Item[]
     */
    protected $items;

    /**
     * Customer information.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Delivery address.
     *
     * @var Address
     */
    protected $deliveryAddress;

    /**
     * Invoicing address.
     *
     * @var Address
     */
    protected $invoicingAddress;

    /**
     * Where to redirect browser after a payment is paid or cancelled.
     *
     * @var CallbackUrl;
     */
    protected $redirectUrls;

    /**
     * Which url to ping after this payment is paid or cancelled.
     *
     * @var CallbackUrl;
     */
    protected $callbackUrls;

    /**
     * Callback URL polling delay in seconds
     *
     * @var int
     */
    protected $callbackDelay;


    /**
     * Return only given groups
     *
     * @var string[]
     */
    protected $groups;

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp() : ?string
    {

        return $this->stamp;
    }

    /**
     * Set the stamp.
     *
     * @param string $stamp
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setStamp(?string $stamp): PaymentRequestInterface
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference() : ?string
    {

        return $this->reference;
    }

    /**
     * Set the reference.
     *
     * @param string $reference
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setReference(?string $reference): PaymentRequestInterface
    {

        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : ?int
    {

        return $this->amount;
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setAmount(?int $amount) : PaymentRequestInterface
    {

        $this->amount = $amount;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency() : ?string
    {

        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCurrency(?string $currency) : PaymentRequestInterface
    {

        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the language.
     *
     * @return string
     */
    public function getLanguage() : ?string
    {

        return $this->language;
    }

    /**
     * Set the language.
     *
     * @param string $language
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setLanguage(?string $language) : PaymentRequestInterface
    {

        $this->language = $language;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return ItemInterface[]
     */
    public function getItems() : ?array
    {

        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param ItemInterface[] $items
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setItems(?array $items) : PaymentRequestInterface
    {

        $this->items = array_values($items);

        return $this;
    }

    /**
     * Get the customer.
     *
     * @return CustomerInterface
     */
    public function getCustomer() : ?CustomerInterface
    {

        return $this->customer;
    }

    /**
     * Set the customer.
     *
     * @param CustomerInterface $customer
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCustomer(?CustomerInterface $customer) : PaymentRequestInterface
    {

        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the delivery address.
     *
     * @return AddressInterface
     */
    public function getDeliveryAddress() : ?AddressInterface
    {

        return $this->deliveryAddress;
    }

    /**
     * Set the delivery address.
     *
     * @param AddressInterface $deliveryAddress
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setDeliveryAddress(?AddressInterface $deliveryAddress) : PaymentRequestInterface
    {

        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get the invoicing address.
     *
     * @return AddressInterface
     */
    public function getInvoicingAddress() : ?AddressInterface
    {

        return $this->invoicingAddress;
    }

    /**
     * Set the invoicing address.
     *
     * @param AddressInterface $invoicingAddress
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setInvoicingAddress(?AddressInterface $invoicingAddress) : PaymentRequestInterface
    {

        $this->invoicingAddress = $invoicingAddress;

        return $this;
    }

    /**
     * Get the redirect urls.
     *
     * @return CallbackUrlInterface
     */
    public function getRedirectUrls() : ?CallbackUrlInterface
    {

        return $this->redirectUrls;
    }

    /**
     * Set the redirect urls.
     *
     * @param CallbackUrlInterface $redirectUrls
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setRedirectUrls(?CallbackUrlInterface $redirectUrls) : PaymentRequestInterface
    {

        $this->redirectUrls = $redirectUrls;

        return $this;
    }

    /**
     * Get callback urls.
     *
     * @return CallbackUrlInterface
     */
    public function getCallbackUrls() : ?CallbackUrlInterface
    {

        return $this->callbackUrls;
    }

    /**
     * Set callback urls.
     *
     * @param CallbackUrl $callbackUrls
     *
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCallbackUrls(?CallbackUrlInterface $callbackUrls) : PaymentRequestInterface
    {

        $this->callbackUrls = $callbackUrls;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallbackDelay(): int
    {
        return $this->callbackDelay;
    }

    /**
     * @param int $callbackDelay
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setCallbackDelay(int $callbackDelay) : PaymentRequestInterface
    {
        $this->callbackDelay = $callbackDelay;

        return $this;
    }

    /**
     * @param array $groups
     * @return PaymentRequestInterface Return self to enable chaining.
     */
    public function setGroups(array $groups) : PaymentRequestInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
