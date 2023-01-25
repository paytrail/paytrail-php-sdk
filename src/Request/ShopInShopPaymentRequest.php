<?php

/**
 * Class PaymentRequest
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Model\Item;

/**
 * Class PaymentRequest
 *
 * This class is used to create a payment request object for
 * the Paytrail\SDK\Client class.
 *
 * @see https://Paytrail.github.io/api-documentation/#/?id=create-request-body
 * @package Paytrail\SDK\Request
 */
class ShopInShopPaymentRequest extends PaymentRequest
{
    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        parent::validate();

        // Validate the shop-in-shop items.
        array_walk($this->items, function (Item $item) {
            $item->validateShopInShop();
        });

        return true;
    }
}
