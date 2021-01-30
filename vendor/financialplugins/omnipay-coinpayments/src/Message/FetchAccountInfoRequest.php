<?php


namespace Omnipay\Coinpayments\Message;

class FetchAccountInfoRequest extends AbstractRequest
{
    protected function getCommand(): string
    {
        return 'get_basic_info';
    }    
}
