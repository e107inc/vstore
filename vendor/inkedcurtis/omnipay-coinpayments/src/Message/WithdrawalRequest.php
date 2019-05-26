<?php

namespace Omnipay\Coinpayments\Message;

use GuzzleHttp\Exception\BadResponseException;

class WithdrawalRequest extends AbstractRequest
{

    public function getPbnTag()
    {
        return $this->getParameter('pbntag');
    }

    public function setPbgTag($value)
    {
        return $this->setParameter('pbntag', $value);
    }

    public function getAutoConfirm()
    {
        return $this->getParameter('auto_confirm');
    }

    public function setAutoConfirm($value)
    {
        return $this->setParameter('auto_confirm', $value);
    }

    public function getNote()
    {
        return $this->getParameter('note');
    }

    public function setNote($value)
    {
        return $this->setParameter('note', $value);
    }

    public function getDestTag()
    {
        return $this->getParameter('dest_tag');
    }

    public function setDestTag($value)
    {
        return $this->setParameter('dest_tag', $value);
    }

    public function getAddTxFee()
    {
        return $this->getParameter('add_tx_fee');
    }

    public function setAddTxFee($value)
    {
        return $this->setParameter('add_tx_fee', $value);
    }

    public function getData()
    {
        $this->validate(
            'amount',
            'currency'
        );
        return [
            'cmd' => 'create_withdrawal',
            'amount' => $this->getAmount(),
            'add_tx_fee' => $this->getAddTxFee(),
            'currency' => $this->getCurrency(),
            'currency2' => $this->getCurrency2(),
            'address' => $this->getAddress(),
            'pbntag' => $this->getPbnTag(),
            'dest_tag' => $this->getDestTag(),
            'ipn_url' => $this->getIPNUrl(),
            'auto_confirm' => $this->getAutoConfirm(),
            'note' => $this->getNote()
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
        $hmac = $this->getSig($data, 'create_withdrawal');

        $data['version'] = 1;
        $data['cmd'] = 'create_withdrawal';
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