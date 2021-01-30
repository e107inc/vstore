<?php

namespace Omnipay\Coinpayments\Message;

class CreateWithdrawalRequest extends AbstractRequest
{
    protected $responseClass = CreateWithdrawalResponse::class;

    protected function getCommand(): string
    {
        return 'create_withdrawal';
    }

    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'payment_currency',
            'description',
            'address',
            'auto_confirm',
            'notify_url'
        );

        $data = parent::getData();

        $data['amount'] = $this->getAmount();
        // The cryptocurrency to withdraw. (BTC, LTC, etc.)
        $data['currency'] = $this->getPaymentCurrency();
        // Optional currency to use to to withdraw 'amount' worth of 'currency2' in 'currency' coin.
        // This is for exchange rate calculation only and will not convert coins or change which currency is withdrawn.
        // For example, to withdraw 1.00 USD worth of BTC you would specify 'currency'='BTC', 'currency2'='USD',
        // and 'amount'='1.00'
        $data['currency2'] = $this->getCurrency();
        $data['address'] = $this->getAddress();
        $data['auto_confirm'] = $this->getAutoConfirm();
        $data['note'] = $this->getDescription();
        $data['ipn_url'] = $this->getNotifyUrl();

        return $data;
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getPaymentCurrency()
    {
        return $this->getParameter('payment_currency');
    }

    public function setPaymentCurrency($value)
    {
        return $this->setParameter('payment_currency', $value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function getAddress()
    {
        return $this->getParameter('address');
    }

    public function setAddress($value)
    {
        return $this->setParameter('address', $value);
    }

    public function getAutoConfirm()
    {
        return $this->getParameter('auto_confirm');
    }

    public function setAutoConfirm($value)
    {
        return $this->setParameter('auto_confirm', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
    }
}
