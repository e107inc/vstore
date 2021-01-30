<?php


namespace Omnipay\Coinpayments\Message;

class FetchTransactionRequest extends AbstractRequest
{
    protected function getCommand(): string
    {
        return 'get_tx_info';
    }

    public function getData()
    {
        $this->validate(
            'transactionReference'
        );

        $data = parent::getData();

        $data['txid'] = $this->getTransactionReference(); // this method is already implemented in the parent AbstractRequest class

        return $data;
    }
}
