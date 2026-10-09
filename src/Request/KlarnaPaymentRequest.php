<?php

/**
 * Class KlarnaPaymentRequest
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;

/**
 * Class KlarnaPaymentRequest
 *
 * Request body for /payments/klarna/charge and /payments/klarna/authorization-hold.
 * Same body as /payments, but providerDetails.klarna.networkSessionToken is required.
 *
 * @see https://docs.paytrail.com/#/?id=conversion-features
 * @package Paytrail\SDK\Request
 */
class KlarnaPaymentRequest extends AbstractPaymentRequest
{
    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $klarna = $this->providerDetails ? $this->providerDetails->getKlarna() : null;

        if ($klarna === null || empty($klarna->getNetworkSessionToken())) {
            throw new ValidationException('Klarna networkSessionToken is empty');
        }

        return parent::validate();
    }
}
