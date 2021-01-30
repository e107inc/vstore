<?php

namespace Omnipay\Coinpayments\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data->error) && $this->data->error == 'ok' && isset($this->data->result);
    }    

    /**
     * Get error message
     * 
     * @return string|null
     */
    public function getMessage()
    {
        return isset($this->data->error) && $this->data->error != 'ok' ? $this->data->error : NULL;
    }

    /**
     * Get the response data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data->result;
    }
}
