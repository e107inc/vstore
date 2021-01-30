<?php

namespace Omnipay\Stark;

use Omnipay\Common\AbstractGateway;

/**
 * Stark Payment Gateway
 *
 * @link https://github.com/starkpay/omnipay
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Stark';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'apiKey' => ''
        );
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\FetchPaymentMethodsRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\RefundRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\CreateCustomerRequest
     */
    public function createCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\CreateCustomerRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\UpdateCustomerRequest
     */
    public function updateCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\UpdateCustomerRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Stark\Message\FetchCustomerRequest
     */
    public function fetchCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stark\Message\FetchCustomerRequest', $parameters);
    }
}
