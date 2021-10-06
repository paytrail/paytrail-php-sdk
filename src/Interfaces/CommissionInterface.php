<?php
/**
 * Interface Commission
 */

namespace Paytrail\SDK\Interfaces;

use Paytrail\SDK\Model\Commission;
use Paytrail\SDK\Exception\ValidationException;

/**
 * Interface Commission
 *
 * An interface for all Commission classes to implement.
 *
 * @package Paytrail\SDK
 */
interface CommissionInterface
{
     /**
      * Validates with Respect\Validation library and throws an exception for invalid objects
      *
      * @throws ValidationException
      */
     public function validate();

     /**
      * The setter for the merchant.
      *
      * @param string $merchant
      * @return Commission Return self to enable chaining.
      */
     public function setMerchant(string $merchant): Commission;

     /**
      * The getter for the merchant.
      *
      * @return string
      */
     public function getMerchant(): string;

     /**
      * The setter for the amount.
      *
      * @param string $amount
      * @return Commission Return self to enable chaining.
      */
     public function setAmount(int $amount): Commission;

     /**
      * The getter for the amount.
      *
      * @return string
      */
     public function getAmount(): int;
}
