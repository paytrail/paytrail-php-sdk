<?php
declare(strict_types=1);

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Util\JsonSerializable;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Interfaces\CommissionInterface;

/**
 * Class Commission
 *
 * @package Paytrail\SDK\Model
 */
class Commission implements \JsonSerializable, CommissionInterface
 {    
     use JsonSerializable;

     /**
      * Validate commission
      *
      * @throws ValidationException
      */
     public function validate()
     {
         $props = get_object_vars($this);

         if (empty($props['merchant'])) {
             throw new ValidationException('Merchant is empty');
         }

         if (filter_var($props['amount'], FILTER_VALIDATE_INT) === false) {
             throw new ValidationException('Amount is not an integer');
         }

         return true;
     }

     /**
      * Merchant identifier for the commission.
      *
      * @var string
      */
     protected $merchant;

     /**
      * Total amount to refund this item, in currency's minor units.
      *
      * @var int
      */
     protected $amount;

     /**
      * The setter for the merchant.
      *
      * @param string $merchant
      * @return Commission Return self to enable chaining.
      */
     public function setMerchant(string $merchant) : Commission
     {
         $this->merchant = $merchant;

         return $this;
     }

     /**
      * The getter for the merchant.
      *
      * @return string
      */
     public function getMerchant() : string
     {
         return $this->merchant;
     }

     /**
      * The setter for the amount.
      *
      * @param int $amount
      * @return Commission Return self to enable chaining.
      */
     public function setAmount(int $amount) : Commission
     {
         $this->amount = $amount;

         return $this;
     }

     /**
      * The getter for the amount.
      *
      * @return string
      */
     public function getAmount() : int
     {
         return $this->amount;
     }
}
