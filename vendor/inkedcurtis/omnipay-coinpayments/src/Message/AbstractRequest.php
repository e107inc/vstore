<?php

namespace Omnipay\Coinpayments\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest {

    protected $liveEndpoint = "https://www.coinpayments.net/api.php";

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

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getCurrency1()
    {
        return $this->getParameter('currency1');
    }

    public function setCurrency1($value)
    {
        return $this->setParameter('currency1', $value);
    }

    public function getCurrency2()
    {
        return $this->getParameter('currency2');
    }

    public function setCurrency2($value)
    {
        return $this->setParameter('currency2', $value);
    }

    public function getAddress()
    {
        return $this->getParameter('address');
    }

    public function setAddress($value)
    {
        return $this->setParameter('address', $value);
    }

    public function getBuyerEmail()
    {
        return $this->getParameter('buyer_email');
    }

    public function setBuyerEmail($value)
    {
        return $this->setParameter('buyer_email', $value);
    }

    public function getBuyerName()
    {
        return $this->getParameter('buyer_name');
    }

    public function setBuyerName($value)
    {
        return $this->setParameter('buyer_name', $value);
    }

    public function getItemName()
    {
        return $this->getParameter('item_name');
    }

    public function setItemName($value)
    {
        return $this->setParameter('item_name', $value);
    }

    public function getItemNumber()
    {
        return $this->getParameter('item_number');
    }

    public function setItemNumber($value)
    {
        return $this->setParameter('item_number', $value);
    }

    public function getInvoice()
    {
        return $this->getParameter('invoice');
    }

    public function setInvoice($value)
    {
        return $this->setParameter('invoice', $value);
    }

    public function getCustom()
    {
        return $this->getParameter('custom');
    }

    public function setCustom($value)
    {
        return $this->setParameter('custom', $value);
    }

    public function getIPNUrl()
    {
        return $this->getParameter('ipn_url');
    }

    public function setIPNUrl($value)
    {
        return $this->setParameter('ipn_url', $value);
    }

    protected function getSig($req, $cmd)
    {
        $req['version'] = 1;
        $req['cmd'] = $cmd;
        $req['key'] = $this->getPublicKey();
        $req['format'] = 'json'; //supported values are json and xml

        foreach($req as $key => $item) {
            if($item == "")
                unset($req[$key]);
        }

        // Generate the query string
        $post_data = http_build_query($req, '', '&');

        // Calculate the HMAC signature on the POST data
        $hmac = hash_hmac('sha512', $post_data, $this->getPrivateKey());

        return $hmac;
    }

    protected function getEndpoint()
    {
        return $this->liveEndpoint;
    }

}