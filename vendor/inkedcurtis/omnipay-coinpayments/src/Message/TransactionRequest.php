<?php

namespace Omnipay\Coinpayments\Message;

use GuzzleHttp\Exception\BadResponseException;

class TransactionRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate(
            'amount',
            'currency1',
            'currency2'
        );
        return [
            'cmd' => 'create_transaction',
            'amount' => $this->getAmount(),
            'currency1' => $this->getCurrency1(),
            'currency2' => $this->getCurrency2(),
            'address' => $this->getAddress(),
            'buyer_email' => $this->getBuyerEmail(),
            'buyer_name' => $this->getBuyername(),
            'item_name' => $this->getItemName(),
            'item_number' => $this->getItemNumber(),
            'invoice' => $this->getInvoice(),
            'custom' => $this->getCustom(),
            'ipn_url' => $this->getIPNUrl(),
        ];
    }

    protected function getHeaders($hmac)
    {
        return [
            'HMAC' => $hmac,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
    }

    public function sendData($data)
    {
        $hmac = $this->getSig($data, 'create_transaction');

        $data['version'] = 1;
        $data['cmd'] = 'create_transaction';
        $data['key'] = $this->getPublicKey();
        $data['format'] = 'json';

        try {
            $response = $this->httpClient->request('POST', $this->getEndpoint(), $this->getHeaders($hmac), http_build_query($data));
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        $result = json_decode($response->getBody()->getContents(), true);
        return new TransactionResponse($this, $result);
    }

}