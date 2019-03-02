# Omnipay: CoinPayments

**CoinPayments driver for the Omnipay PHP payment processing library**

[![Latest Stable Version](https://poser.pugx.org/inkedcurtis/omnipay-coinpayments/version)](https://packagist.org/packages/inkedcurtis/omnipay-coinpayments)
[![Total Downloads](https://poser.pugx.org/inkedcurtis/omnipay-coinpayments/d/total.png)](https://packagist.org/packages/inkedcurtis/omnipay-coinpayments)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements CoinPayments support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "inkedcurtis/omnipay-coinpayments": "^1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Payssion

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Example

### Create a transaction

```php
$gateway = Omnipay::create('Coinpayments');

$gateway->initialize(array(
    'publicKey' => '',
    'privateKey => ''
));

$response = $gateway->transaction([
    'amount' => 10.00,
    'currency1' => 'USD',
    'currency2' => 'BTC',
    //'address' => '', // leave blank send to follow your settings on the Coin Settings page
    'item_name' => 'Test Item/Order Description',
    'ipn_url' => 'https://yourserver.com/ipn_handler.php',
])->send();

if ($response->isSuccessful()) {
    $data = $response->getData(); 
}
```

### Make a withdrawal

```php
$gateway = Omnipay::create('Coinpayments');

$gateway->initialize(array(
    'publicKey' => '',
    'privateKey => ''
));

$response = $gateway->withdrawal([
    'amount' => 0.1,
    'currency' => 'BTC',
    'address' => '1LC9Tn7ekRXhMTzh7ZJnZ55XUBM4ZGuLhJ'
])->send();

if ($response->isSuccessful()) {
    $data = $response->getData(); 
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/inkedcurtis/omnipay-coinpayments/issues),
or better yet, fork the library and submit a pull request.