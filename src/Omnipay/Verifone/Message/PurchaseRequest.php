<?php
namespace Omnipay\Verifone\Message;
use DOMDocument;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;
/**
 * Verifone Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'TBA';
    protected $testEndpoint = 'https://txn-test.cxmlpg.com/XML4/commideagateway.asmx';
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }
    public function getPassword()
    {
        return $this->getParameter('password');
    }
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
    public function getData()
    {
        $this->validate('amount', 'card', 'transactionId');
        $this->getCard()->validate();
        $data = new SimpleXMLElement('<Request/>');
        $data->Authentication->client = $this->getMerchantId();
        $data->Authentication->password = $this->getPassword();
        $data->Transaction->TxnDetails->amount = $this->getAmount();
        $data->Transaction->TxnDetails->amount->addAttribute('currency', $this->getCurrency());
        $data->Transaction->TxnDetails->merchantreference = $this->getTransactionId();
        $data->Transaction->CardTxn->Card->pan = $this->getCard()->getNumber();
        $data->Transaction->CardTxn->Card->expirydate = $this->getCard()->getExpiryDate('m/y');
        $data->Transaction->CardTxn->method = 'auth';
        return $data;
    }
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
    /**
     * @param SimpleXMLElement $data
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     */
    public function sendData($data)
    {
        // post to Verifone
        $xml = $data->saveXML();
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $xml)->send();
        return $this->response = new Response($this, $httpResponse->getBody());
    }
}