# Paytrail PHP-SDK
PHP Software Development Kit for [Paytrail](https://www.paytrail.com) payment service

## Paytrail Payment Service

Paytrail is a payment gateway that offers 20+ payment methods for Finnish customers.

The payment gateway provides all the popular payment methods with one simple integration. The provided payment methods include, but are not limited to, credit cards, online banking and mobile payments.

To use the payment service, you need to sign up for a Paytrail account. Transaction fees will be charged for every transaction. Transaction cost may vary from merchant to merchant, based on what is agreed upon with Paytrail when negotiating your contract. For more information and registration, please visit our [website](https://www.paytrail.com) or contact asiakaspalvelu@paytrail.com directly.

## Requirements

### General requirements

- PHP version >= 7.3
- ext-curl PHP cURL extension
- ext-json PHP JSON extension

### Development requirements

- [PHPUnit](https://github.com/sebastianbergmann/phpunit) - A programmer-oriented testing framework for running unit tests in PHP.
- [Guzzle](https://github.com/guzzle/guzzle) 7 or 6 - PHP HTTP client for performing HTTP request.

### Guzzle
PHP-SDK will use Guzzle 6 or 7, if in present application, otherwise it will fall back to cURL. Guzzle is still used as dev dependency to make testing easier.

Using Guzzle is recommended on applications using PHP-SDK.

## Installation

Install with Composer:

```
composer require paytrail/paytrail-php-sdk
```

The package uses PSR-4 autoloader. Activate autoloading by requiring the Composer autoloader:

```
require 'vendor/autoload.php';
```

_Note the path to the vendor directory is relative to your project._

## Folder contents & descriptions

| Folder/File    | Content/Description                                                      |
|----------------|--------------------------------------------------------------------------|
| examples       | Examples                                                                 |
| src/Exception  | Exception classes and functions                                          |
| src/Interfaces | Interface classes and functions for all the related classes to implement |
| src/Model      | Model classes and functions                                              |
| src/Request    | Request classes and functions                                            |
| src/Response   | Response classes and functions                                           |
| src/Util       | Utility/trait classes and functions                                      |
| src/Client.php | Client class and functions                                               |
| tests/unit     | PHP unit tests                                                           |

## Basic functionalities

The Paytrail PHP-SDK supports most of the functionalities of the [Paytrail Payment API](https://paytrail.github.io/api-documentation/#/).

Some of the key features are:

### Payments and refunds

- [Creating payment request](https://paytrail.github.io/api-documentation/#/?id=create)
- [Creating payment status request](https://paytrail.github.io/api-documentation/#/?id=get)
- [Creating refund request](https://paytrail.github.io/api-documentation/#/?id=refund)

### Tokenized credit cards and payments

- [Creating Add card form request](https://paytrail.github.io/api-documentation/#/?id=adding-tokenizing-cards)
- [Creating Get token request](https://paytrail.github.io/api-documentation/#/?id=get-token)
- [Creating Customer Initiated Transactions (CIT) or Merchant Initiated Transactions (MIT)](https://checkoutfinland.github.io/psp-api/#/?id=charging-a-token)

### Shop-in-Shop

- Creating Shop-in-Shop payment request

### Settlements

- [Requesting merchant settlements](https://docs.paytrail.com/#/?id=settlements)

### Reports

- [Request payment report](https://docs.paytrail.com/#/?id=payment-report-request)

## Methods

List of `Client::class` methods

| Method                              | Description                                          |
|-------------------------------------|------------------------------------------------------|
| getPaymentProviders()               | Get a list of payment providers                      |
| getGroupedPaymentProviders()        | Returns an array of grouped payment providers fields |
| createPayment()                     | Create payment                                       |
| createShopInShopPayment()           | Create SiS payment                                   |
| getPaymentStatus()                  | Request payment status                               |
| refund()                            | Create refund                                        |
| emailRefund()                       | Create email refund                                  |
| createAddCardFormRequest()          | Save card details                                    |
| createGetTokenRequest()             | Request card token                                   |
| createCitPaymentCharge()            | Create CiT payment                                   |
| createCitPaymentAuthorizationHold() | Create CiT authorization hold                        |
| createCitPaymentCommit()            | Commit CiT authorization hold                        |
| createMitPaymentCharge()            | Create MiT payment                                   |
| createMitPaymentAuthorizationHold() | Create MiT authorization hold                        |
| createMitPaymentCommit()            | Commit MiT authorization hold                        |
| revertPaymentAuthorizationHold()    | Revert existing Mit or CiT authorization hold        |
| getSettlements()                    | Request settlements                                  |
| requestPaymentReport()              |  Request payment report                              |


---

**_Disclaimer:_** *This open source project is made available to assist coders in getting started with our API. However, we do not provide any warranty or guarantee that the code will work as intended and offer limited support for it. Use at your own risk.*
