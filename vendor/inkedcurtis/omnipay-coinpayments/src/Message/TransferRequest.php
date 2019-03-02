<?php

namespace Omnipay\Coinpayments\Message;

use GuzzleHttp\Exception\BadResponseException;

class TransferRequest extends AbstractRequest
{

    public function getAutoConfirm()
    {
        return $this->getParameter('auto_confirm');
    }

    public function setAutoConfirm($value)
    {
        return $this->setParameter('auto_confirm', $value);
    }

    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    public function setMerchant($value)
    {
        return $this->setParameter('merchant', $value);
    }

    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'merchant'
        );
        return [
            'cmd' => 'create_transfer',
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'merchant' => $this->getMerchant(),
            'auto_confirm' => $this->getAutoConfirm()
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
        $hmac = $this->getSig($data, 'create_transfer');

        $data['version'] = 1;
        $data['cmd'] = 'create_transfer';
        $data['key'] = $this->getPublicKey();
        $data['format'] = 'json';

        try {
            $response = $this->httpClient->request('POST', $this->getEndpoint(), $this->getHeaders($hmac), http_build_query($data));
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        $result = json_decode($response->getBody()->getContents(), true);
        return new PayByNameResponse($this, $result);
    }

}