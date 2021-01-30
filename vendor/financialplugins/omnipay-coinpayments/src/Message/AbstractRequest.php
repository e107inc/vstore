<?php

namespace Omnipay\Coinpayments\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    private $version = 1;
    private $endpoint = 'https://www.coinpayments.net/api.php';

    protected $responseClass = Response::class;

    abstract protected function getCommand(): string;

    /**
     * Get a single parameter.
     * Overriding the parent method to change its visibility from protected to public
     *
     * @param string $key The parameter key
     * @return mixed
     */
    /*public function getParameter($key)
    {
        return parent::getParameter($key);
    }*/

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getPublicKey(): string
    {
        return $this->getParameter('public_key');
    }

    public function setPublicKey(string $value)
    {
        return $this->setParameter('public_key', $value);
    }

    public function getPrivateKey(): string
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey(string $value)
    {
        return $this->setParameter('private_key', $value);
    }

    public function getData()
    {
        return [
            'version'   => $this->getVersion(),
            'cmd'       => $this->getCommand(),
            'key'       => $this->getPublicKey(),
            'format'    => 'json'
        ];
    }

    public function sendData($data)
    {
        $body = http_build_query($data, '', '&');

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'HMAC' => hash_hmac('sha512', $body, $this->getPrivateKey())
        ];

        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), $headers, $body);

        return $this->createResponse(json_decode($httpResponse->getBody()->getContents()), $httpResponse->getHeaders());
    }

    protected function createResponse($data, array $headers = []): Response
    {
        return $this->response = new $this->responseClass($this, $data, $headers);
    }
}
