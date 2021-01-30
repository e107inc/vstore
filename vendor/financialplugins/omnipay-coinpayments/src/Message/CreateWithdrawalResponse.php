<?php

namespace Omnipay\Coinpayments\Message;

class CreateWithdrawalResponse extends Response
{
    public function getTransactionReference()
    {
        return $this->getData()->id;
    }
}
