<?php


namespace Omnipay\Coinpayments\Message;

class FetchBalanceRequest extends AbstractRequest
{
    protected function getCommand(): string
    {
        return 'balances';
    }
}
