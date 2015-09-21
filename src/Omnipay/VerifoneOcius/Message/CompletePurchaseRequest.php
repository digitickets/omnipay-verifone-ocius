<?php
namespace Omnipay\VerifoneOcius\Message;

use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Verifone Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        return $this->httpRequest->request->all();
    }
    public function sendData($data)
    {
        return $this->response = new Response($this, $data);
    }
}
