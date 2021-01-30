# Omnipay: Stark

**Stark driver for the Omnipay PHP payment library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Stark support for Omnipay so you can accept crypto currency payments.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `starkpay/omnipay` with Composer:

```
composer require league/omnipay starkpay/omnipay
```


## Basic Usage

This package provide Stark Payment Solution.

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

### Basic purchase example

API key will be avilable in your Stark Merchant Console. Visit : (https://dashboard.starkpayments.net/)

```php
$gateway = \Omnipay\Omnipay::create('Stark');  
$gateway->setApiKey('key_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM');

$response = $gateway->purchase(
    [
        "amount" => "10.00",
        "currency" => "EUR",
        "description" => "My first Payment",
        "returnUrl" => "https://webshop.example.org/return-page.php"
    ]
)->send();

// Process response
if ($response->isSuccessful()) {

    // Payment was successful
    print_r($response);

} elseif ($response->isRedirect()) {
    // You can get Transaction id by  $response->getTransactionId();
    // Redirect to offsite payment gateway
    $response->redirect();

} else {

    // Payment failed
    echo $response->getMessage();
}
```

##### Response Page

We will post the transaction `id` & the `status` to your `returnUrl` during purchase. Once the transaction is completed you will get a [webhook](https://en.wikipedia.org/wiki/Webhook) notification which is configured in dashboard.starkpayments.net

Example Return Page : return-page.php
```php
echo "Your transaction id is : " . $_POST['id'] ." and status is : ".$_POST['status'];
```


#### Fetch Transaction Details

Fetch Transaction details using transaction id

```php
$gateway = Omnipay::create('Stark');

$gateway->setApiKey('key_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM');

// Send purchase request
$response = $gateway->fetchTransaction([
    'transactionReference' => '5eb165796a7b7'
])->send();

// Process response
if ($response->isSuccessful()) {
    // Transaction Details
    print_r($response->getMetaData());
    /*
    Sample Response
    Array
    (
        [id] => 5eb165796a7b7
        [amount] => 15.00
        [currency] => USD
        [cypto_amount] => 0.001694
        [crypto] => BTC
        [status] => success
        [customer_name] => John Doe
        [customer_email] => john.doe@mail.com
    )
     */
} else {
    // Get Error Message
    echo $response->getMessage();
}
```

#### Payment Status Details

Status | Description
--- | ---  
processing | Payment is under processing
success | Successfully verified the crypto payment
failed | Payment failed or Cancelled by the customer

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/starkpay/omnipay/issues),
or better yet, fork the library and submit a pull request.
