<?php


namespace Omnipay\Coinpayments\Message;

class FetchWithdrawalRequest extends AbstractRequest
{
    protected function getCommand(): string
    {
        return 'get_withdrawal_info';
    }

    public function getData()
    {
        $this->validate(
            'withdrawalReference'
        );

        $data = parent::getData();

        $data['id'] = $this->getWithdrawalReference();

        return $data;
    }

    public function getWithdrawalReference(): string
    {
        return $this->getParameter('withdrawalReference');
    }

    public function setWithdrawalReference(string $value)
    {
        return $this->setParameter('withdrawalReference', $value);
    }
}
