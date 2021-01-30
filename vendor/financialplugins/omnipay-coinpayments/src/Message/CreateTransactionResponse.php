<?php

namespace Omnipay\Coinpayments\Message;

class CreateTransactionResponse extends Response
{
    public function isSuccessful()
    {
        // transactions can not be confirmed immediately
        return FALSE;
    }

    public function isRedirect()
    {
        return TRUE;
    }

    public function getTransactionReference()
    {
        return $this->getData()->txn_id;
    }
}
