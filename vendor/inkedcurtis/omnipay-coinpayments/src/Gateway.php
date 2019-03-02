<?php

namespace Omnipay\Coinpayments;

use Omnipay\Common\AbstractGateway;
use Omnipay\Coinpayments\Message\TransactionRequest;
use Omnipay\Coinpayments\Message\PayByNameRequest;
use Omnipay\Coinpayments\Message\TransferRequest;
use Omnipay\Coinpayments\Message\WithdrawalRequest;

class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Coinpayments';
    }

    public function getDefaultParameters()
    {
        return array(
            'privateKey' => '',
            'publicKey' => ''
        );
    }

    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * @return TransactionRequest
     */
    public function transaction(array $parameters = array())
    {
        return $this->createRequest(TransactionRequest::class, $parameters);
    }

    /**
     * @return PayByNameRequest
     */
    public function PayByName(array $parameters = array())
    {
        return $this->createRequest(PayByNameRequest::class, $parameters);
    }

    /**
     * @return TransferRequest
     */
    public function transfer(array $parameters = array())
    {
        return $this->createRequest(TransferRequest::class, $parameters);
    }

    /**
     * @return WithdrawalRequest
     */
    public function withdrawal(array $parameters = array())
    {
        return $this->createRequest(WithdrawalRequest::class, $parameters);
    }

}
