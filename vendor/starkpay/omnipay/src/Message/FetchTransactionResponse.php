<?php

namespace Omnipay\Stark\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class FetchTransactionResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data['links']['paymentUrl']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['links']['paymentUrl'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return parent::isSuccessful();
    }

    /**
     * @return boolean
     */
    public function isOpen()
    {
        return isset($this->data['status']) && 'processing' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isCancelled()
    {
        return isset($this->data['status']) && 'failed' === $this->data['status'];
    }

    /**
     * @return mixed
     */
    public function getTransactionReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        if (isset($this->data['status'])) {
            return $this->data['status'];
        }
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        if (isset($this->data['amount'])) {
            return $this->data['amount'];
        }
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        if (isset($this->data['metadata'])) {
            return $this->data['metadata'];
        }
    }
}
