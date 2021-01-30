# Omnipay: Coinpayments.net gateway
[Coinpayments.net](https://www.coinpayments.net) driver for the [Omnipay](https://omnipay.thephpleague.com) PHP payment processing library.

## Installation
```
composer require financialplugins/omnipay-coinpayments
```
## Usage
### Initialize
This step is required before using other methods.
```
$gateway = Omnipay::create('Coinpayments');
   
$gateway->initialize([
    'merchant_id'   => '...',
    'public_key'    => '...',
    'private_key'   => '...',
    'secret_key'    => '...'
);
```
### Fetch Account Information

```
$response = $gateway->fetchAccountInfo()->send();

if ($response->isSuccessful()) {
    $data = $response->getData();
} else {
    $errorMessage = $response->getMessage();
}
```
### Fetch Account Balance

```
$response = $gateway->fetchBalance()->send();

if ($response->isSuccessful()) {
    $data = $response->getData();
} else {
    $errorMessage = $response->getMessage();
}
```
### Fetch Currencies and Rates

```
$response = $gateway->fetchCurrencies()->send();

if ($response->isSuccessful()) {
    $data = $response->getData();
} else {
    $errorMessage = $response->getMessage();
}
```
### Fetch Transaction Information

```
$response = $gateway
    ->fetchTransaction(['transactionReference' => '...'])
    ->send();

if ($response->isSuccessful()) {
    $data = $response->getData();
} else {
    $errorMessage = $response->getMessage();
}
```
### Fetch Withdrawal Information

```
$response = $gateway
    ->fetchWithdrawal(['withdrawalReference' => '...'])
    ->send();

if ($response->isSuccessful()) {
    $data = $response->getData();
} else {
    $errorMessage = $response->getMessage();
}
```
### Create Transaction

```
$response = $gateway
    ->createTransaction([
        'amount'            => '100',
        'currency'          => 'USD',
        'payment_currency'  => 'BTC',
        'description'       => 'Payment description',
        'client_email'      => 'email@email.com',
        'notify_url'        => 'https://yourwebsite/webhook'
    ])
    ->send();

if ($response->isRedirect()) {
    $data = $response->getData();

    EXAMPLE RESPONSE:
    {
      "amount":"1.00000000",
      "address":"ZZZ",
      "dest_tag":"YYY",
      "txn_id":"XXX",
      "confirms_needed":"10",
      "timeout":9000,
      "checkout_url":"https:\/\/www.coinpayments.net\/index.php?cmd=checkout&id=XXX&key=ZZZ"
      "status_url":"https:\/\/www.coinpayments.net\/index.php?cmd=status&id=XXX&key=ZZZ"
      "qrcode_url":"https:\/\/www.coinpayments.net\/qrgen.php?id=XXX&key=ZZZ"
    }

} else {
    $errorMessage = $response->getMessage();
}
```
### Create Withdrawal

```
$response = $gateway
    ->createWithdrawal([
        'amount'            => '100',
        'currency'          => 'USD',
        'payment_currency'  => 'BTC',
        'description'       => 'Payment description',
        'address'           => 'XXXXXXXXXX',
        'auto_confirm'      => 0,
        'notify_url'        => 'https://yourwebsite/webhook'
    ])
    ->send();

if ($response->isSuccessful()) {
    $data = $response->getData();

    EXAMPLE RESPONSE:
    {
       "error":"ok",
       "result":{
          "id":"hex string",
          "status":0,
          "amount":1.00,
       }
    }

} else {
    $errorMessage = $response->getMessage();
}
```
### Verify IPN signature
```
$success = $gateway->isSignatureValid($payload, $hmacHeader)
```

## Support
If you are having general issues with Omnipay, we suggest posting on [Stack Overflow](http://stackoverflow.com/). Be sure to add the [omnipay](omnipay) tag so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project, or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/financialplugins/omnipay-coinpayments/issues), or better yet, fork the library and submit a pull request.
