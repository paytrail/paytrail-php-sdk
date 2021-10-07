<?php
/**
 * Class Signature
 */

namespace Paytrail\SDK\Util;

use Paytrail\SDK\Exception\HmacException;

/**
 * Class Signature
 *
 * A utility class for creating and validating HMAC signatures.
 *
 * @package Paytrail\SDK\Util
 */
class Signature
{

    /**
     * Calculate Paytrail HMAC
     *
     * For more information about the signature headers:
     * @see https://paytrail.github.io/api-documentation/#/?id=headers-and-request-signing
     * @see https://paytrail.github.io/api-documentation/#/?id=redirect-and-callback-url-parameters
     *
     * @param array[string]  $params    HTTP headers in an associative array.
     *
     * @param string                $body      HTTP request body, empty string for GET requests
     * @param string                $secretKey The merchant secret key.
     * @return string SHA-256 HMAC
     */
    public static function calculateHmac(array $params = [], string $body = '', string $secretKey = '')
    {
        // Keep only checkout- params, more relevant for response validation.
        $includedKeys = array_filter(array_keys($params), function ($key) {
            return preg_match('/^checkout-/', $key);
        });

        // Keys must be sorted alphabetically
        sort($includedKeys, SORT_STRING);

        $hmacPayload = array_map(
            function ($key) use ($params) {
                // Responses have headers in an array.
                $param = is_array($params[ $key ]) ? $params[ $key ][0] : $params[ $key ];

                return join(':', [ $key, $param ]);
            },
            $includedKeys
        );

        array_push($hmacPayload, $body);

        return hash_hmac('sha256', join("\n", $hmacPayload), $secretKey);
    }

    /**
     * Evaluate a response signature validity.
     *
     * For more information about the signature headers:
     * @see https://paytrail.github.io/api-documentation/#/?id=headers-and-request-signing
     * @see https://paytrail.github.io/api-documentation/#/?id=redirect-and-callback-url-parameters
     *
     * @param array  $params    The response parameters.
     * @param string $body      The response body.
     * @param string $signature The response signature key.
     * @param string $secretKey The merchant secret key.
     *
     * @throws HmacException
     */
    public static function validateHmac(
        array $params = [],
        string $body = '',
        string $signature = '',
        string $secretKey = ''
    ) {
        $hmac = static::calculateHmac($params, $body, $secretKey);

        if ($hmac !== $signature) {
            throw new HmacException('HMAC signature is invalid.', 401);
        }
    }
}
