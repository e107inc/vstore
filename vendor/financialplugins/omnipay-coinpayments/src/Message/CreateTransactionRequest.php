<?php

namespace Omnipay\Coinpayments\Message;

class CreateTransactionRequest extends AbstractRequest
{
    protected $responseClass = CreateTransactionResponse::class;

    protected function getCommand(): string
    {
        return 'create_transaction';
    }

    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'payment_currency',
            'description',
            'client_email',
            'notify_url'
        );

        $data = parent::getData();

        $data['amount'] = $this->getAmount();
        $data['currency1'] = $this->getCurrency();
        $data['currency2'] = $this->getPaymentCurrency();
        $data['item_name'] = $this->getDescription();
        $data['buyer_email'] = $this->getClientEmail();
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

    public function getClientEmail()
    {
        return $this->getParameter('client_email');
    }

    public function setClientEmail($value)
    {
        return $this->setParameter('client_email', $value);
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
