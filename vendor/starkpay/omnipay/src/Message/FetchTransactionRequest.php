<?php

namespace Omnipay\Stark\Message;

/**
 * Starkpay Fetch Transaction Request
 *
 * @method \Omnipay\Stark\Message\FetchTransactionResponse send()
 */
class FetchTransactionRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = array();
        $data['id'] = $this->getTransactionReference();

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->sendRequest('GET', '/transaction/' . $data['id']);

        return $this->response = new FetchTransactionResponse($this, $response);
    }
}
