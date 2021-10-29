<?php

require 'vendor/autoload.php';

use Guzzle6\Exception\RequestException;
use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\HmacException;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Request\PaymentRequest;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Address;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Response\PaymentResponse;

/**
 * Class Payment
 */
class Payment
{
    /** @var array $exampleItems  */
    protected $exampleItems = array (
        array (
                'title' => 'Example 1',
                'amount' => 1,
                'price' => 5.0,
                'code' => 'example01',
                'vat' => 24
        ),
        array (
            'title' => 'Example 2',
            'amount' => 2,
            'price' => 2.5,
            'code' => 'example02',
            'vat' => 24
        )
    );

    private $base_url;

    /**
     * Handle payment data and create payment with SDK client
     *
     * @param $data
     *
     * @return PaymentResponse|string
     */
    public function processPayment($data) {

        try {
            $this->base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $response['error'] = null;
            $client = new Client(
                375917,
                'SAIPPUAKAUPPIAS',
                'php-sdk-test'
            );

            $payment = new PaymentRequest();

            $this->setPaymentData($payment, $data);

            $response['data'] = $client->createPayment($payment);

            return $response;

        } catch (RequestException $e) {
            $response['error'] = $e->getMessage();

        } catch (HmacException $e) {
            $response['error'] = $e->getMessage();

        } catch (ValidationException $e) {
            $response['error'] = $e->getMessage();

        }
        return $response;

    }

    /**
     * Set data for Payment Request
     *
     * @param PaymentRequest $payment
     * @param array $data
     *
     * @return PaymentRequest
     */
    public function setPaymentData($payment, $data) {

        $payment->setStamp(hash('sha256', time()));

        $payment->setAmount($data['amount'] * 100);

        $payment->setReference('your order reference');

        $payment->setCurrency('EUR');

        $payment->setLanguage($data['country']);

        $customer = $this->createCustomer($data);

        $payment->setCustomer($customer);

        $invoicingAddress = $this->createAddress($data);

        $payment->setInvoicingAddress($invoicingAddress);
        $payment->setDeliveryAddress($invoicingAddress);

        $items = $this->mapOrderItems();

        $payment->setItems($items);

        $payment->setRedirectUrls($this->createRedirectUrl());

        $payment->setCallbackUrls($this->createCallbackUrl());

        return $payment;
    }

    /**
     * Set data for Customer model
     *
     * @param array $data
     *
     * @return Customer
     */
    private function createCustomer($data) {

        $customer = new Customer();

        $customer->setEmail($data['email'])
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setPhone($data['phone']);

        return $customer;
    }

    /**
     * Set data for Address model
     *
     * @param array $data
     *
     * @return Address
     */
    private function createAddress($data) {

        $address = new Address();

        $address->setStreetAddress( $data['address'])
            ->setPostalCode( $data['postalCode'])
            ->setCity( $data['city'])
            ->setCounty( $data['county'])
            ->setCountry( $data['country']);

        return $address;
    }

    /**
     * Set order items data.
     * The actual order items must exist or this function does nothing.
     *
     * return array
     */
    private function mapOrderItems() {

        //Mockup array exampleItems containing order item data
        $orderItems = $this->exampleItems;

        //Loop through and map all order items
        $items = array_map(
            function ($item) {
                return $this->createItems($item);
            },
            $orderItems
        );

        return $items;
    }

    /**
     * Set data for Item model
     */
    private function createItems($item) {

        $orderItem = new Item();

        $orderItem->setUnitPrice(floatval($item['price']) * 100)
            ->setUnits($item['amount'])
            ->setVatPercentage($item['vat'])
            ->setProductCode($item['code'])
            ->setDescription($item['title']);

        return $orderItem;
    }

    /**
     * Set redirect urls
     */
    private function createRedirectUrl() {

        $callback = new CallbackUrl();

        $callback->setSuccess($this->base_url . '/response.php');
        $callback->setCancel($this->base_url . '/response.php');

        return $callback;
    }

    /**
     * Set callback urls
     */
    private function createCallbackUrl() {

        $callback = new CallbackUrl();

        $callback->setSuccess($this->base_url . '/response.php');
        $callback->setCancel($this->base_url . '/response.php');

        return $callback;
    }

}
