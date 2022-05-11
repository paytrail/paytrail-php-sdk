<?php

use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\RequestException;

/**
 * Class Providers
 */
class Providers
{

    /**
     * Get provider group data using SDK client
     *
     * @param $amount
     * @param $locale
     *
     * @return array|string
     */
    public function getProviderGroupData($amount, $locale) {

        try {
            $errorMsg = null;
            $client = new Client(
                375917,
                'SAIPPUAKAUPPIAS',
                'php-sdk-test'
            );

            $providers = $client->getGroupedPaymentProviders($amount, $locale);

            return $providers;

        } catch (RequestException $e) {
            $errorMsg = $e->getMessage();
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
        }
        return $errorMsg;

    }

}
