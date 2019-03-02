<?php

namespace Omnipay\Coinpayments\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Response
 */
class TransactionResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return isset($this->data['error']) && $this->data['error'] == 'ok';
    }

    public function getAmount()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['amount'];
        }
    }

    public function getAddress()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['address'];
        }
    }

    public function getTransactionId()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['txn_id'];
        }
    }

    public function getConfirms()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['confirms_needed'];
        }
    }

    public function getTimeout()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['timeout'];
        }
    }

    public function getStatusUrl()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['status_url'];
        }
    }

    public function getQRCodeUrl()
    {
        if (isset($this->data['result'])) {
            return $this->data['result']['qrcode_url'];
        }
    }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * Get the response data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}