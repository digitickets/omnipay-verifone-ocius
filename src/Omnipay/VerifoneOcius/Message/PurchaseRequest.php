<?php
namespace Omnipay\VerifoneOcius\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;
/**
 * Verifone Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'TBA';
    protected $testEndpoint = 'https://paypage2-test.cxmlpg.com/paypage.aspx';

    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }
    protected function getAccountId()
    {
        return $this->getParameter('accountId');
    }
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }
    public function getSystemGuid()
    {
        return $this->getParameter('systemGuid');
    }
    public function setSystemGuid($value)
    {
        return $this->setParameter('systemGuid', $value);
    }

    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }
    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }
    public function getAllowedPaymentMethods()
    {
        return $this->getParameter('allowedPaymentMethods');
    }
    public function setAllowedPaymentMethods($value)
    {
        return $this->setParameter('allowedPaymentMethods', $value);
    }
    public function getShowPaymentResult()
    {
        return $this->getParameter('showPaymentResult');
    }
    public function setShowPaymentResult($value)
    {
        return $this->setParameter('showPaymentResult', $value);
    }
    public function getCaptureMethod()
    {
        return $this->getParameter('captureMethod');
    }
    public function setCaptureMethod($value)
    {
        return $this->setParameter('captureMethod', $value);
    }
    public function getDeliveryEdit()
    {
        return $this->getParameter('deliveryEdit');
    }
    public function setDeliveryEdit($value)
    {
        return $this->setParameter('deliveryEdit', $value);
    }
    public function getProcessingIdentifier()
    {
        return $this->getParameter('processingIdentifier');
    }
    public function setProcessingIdentifier($value)
    {
        return $this->setParameter('processingIdentifier', $value);
    }
    public function getRegisterToken()
    {
        return $this->getParameter('registerToken');
    }
    public function setRegisterToken($value)
    {
        return $this->setParameter('registerToken', $value);
    }
    public function getShowOrderConfirmation()
    {
        return $this->getParameter('showOrderConfirmation');
    }
    public function setShowOrderConfirmation($value)
    {
        return $this->setParameter('showOrderConfirmation', $value);
    }
    
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getData()
    {
        $data = array('postdata' => $this->getPostdataInputValue());
        
        return $data;
    }
    
    /**
     * @param SimpleXMLElement $data
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    /**
     * Method to return the value of the postdata field in the form that is submitted to Verifone.
     * It's very complicated! It is XML, with another XML document embedded in one of the elements,
     * and then it's double-encoded.
     *
     * @return string
     */
    public function getPostdataInputValue()
    {
        // Build the post data, which contains the request data.
        $postDataXml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><postdata/>');
        $postDataXml->addAttribute('xmlns:xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $postDataXml->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $postDataXml->addChild('api', $this->getApiVersion());
        $postDataXml->addChild('merchantid', $this->getMerchantId());
        $postDataXml->addChild('requesttype', 'eftrequest');
        $postDataXml->addChild('requestdata', $this->getRequestdataInputValue());
        $postDataXml->addChild('keyname');
        
        $postData = $this->doubleEncode($postDataXml->asXML());
        
        return $postData;
    }

    /**
     * Method to return the request data for an eftrequest. It returns the data as XML, and it's
     * then embedded in the postdata data.
     *
     * @return string
     */
    protected function getRequestdataInputValue()
    {
        // Build the request data. This XML gets put into a single element
        // of the post data.
        $requestDataXml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><eftrequest/>');
        $requestDataXml->addAttribute('xmlns:xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $requestDataXml->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $requestDataXml->addChild('accountid', $this->getAccountId());
        $requestDataXml->addChild('allowedpaymentmethods', $this->getAllowedPaymentMethods());
        $requestDataXml->addChild('showpaymentresult', $this->getShowPaymentResult());
        
        $merchantXml = $requestDataXml->addChild('merchant');
        $merchantXml->addChild('merchantid', $this->getMerchantId());
        $merchantXml->addChild('systemguid', $this->getSystemGuid());

        $requestDataXml->addChild('merchantreference', $this->getParameter('transactionId'));
        $requestDataXml->addChild('returnurl', $this->getParameter('returnUrl'));
        $requestDataXml->addChild('template', '');
        $requestDataXml->addChild('capturemethod', $this->getCaptureMethod());

        $customerXml = $requestDataXml->addChild('customer');
        $customerXml->addChild('deliveryedit', $this->getDeliveryEdit());
        $card = $this->getCard();
        if ($card) {
            $customerXml->addChild('email', $card->getEmail());
            $customerXml->addChild('firstname', $card->getFirstName());
            $customerXml->addChild('lastname', $card->getLastName());
            
            $billingAddressXml = $customerXml->addChild('address');
            $billingAddressXml->addChild('address1', $card->getBillingAddress1());
            $billingAddressXml->addChild('address2', $card->getBillingAddress2());
            $billingAddressXml->addChild('country', $card->getBillingCountry());
            $billingAddressXml->addChild('postcode', $card->getBillingPostcode());
            $billingAddressXml->addChild('town', $card->getBillingCity());
            
            $deliveryAddressXml = $customerXml->addChild('deliveryaddress');
            $deliveryAddressXml->addChild('address1', $card->getShippingAddress1());
            $deliveryAddressXml->addChild('address2', $card->getShippingAddress2());
            $deliveryAddressXml->addChild('country', $card->getShippingCountry());
            $deliveryAddressXml->addChild('postcode', $card->getShippingPostcode());
            $deliveryAddressXml->addChild('town', $card->getShippingCity());
        }
        $basketXml = $customerXml->addChild('basket');
        $basketXml->addChild('shippingamount', '0.00');
        $basketXml->addChild('totalamount', $this->getAmount());
        $basketXml->addChild('vatamount', '0.00');

        /**
         * @var \Omnipay\Common\Item $item
         */
        $basketItemsXml = $basketXml->addChild('basketitems');
        foreach($this->getItems() as $item) {
            $basketItemXml = $basketItemsXml->addChild('basketitem');

            $basketItemXml->addChild('productname', $this->doubleEncode($item->getName()));
            $basketItemXml->addChild('quantity', $item->getQuantity());
            $basketItemXml->addChild('unitamount', $item->getPrice());
            $basketItemXml->addChild('vatamount', '0.00');
            $basketItemXml->addChild('vatrate', '0.00');
            $basketItemXml->addChild('lineamount', sprintf('%0.2f', $item->getPrice() * $item->getQuantity()));
        }
        
        $requestDataXml->addChild('processingidentifier', $this->getProcessingIdentifier());
        $requestDataXml->addChild('registertoken', $this->getRegisterToken());
        $requestDataXml->addChild('showorderconfirmation', $this->getShowOrderConfirmation());
        $requestDataXml->addChild('transactionvalue', $this->getAmount());

        return $requestDataXml->asXML();

    }

    /**
     * Method to double-encode the given string.
     * @param $htmlString string
     * @param $flags      int Any specific flags you want to use.
     * 
     * @return string
     */
    protected function doubleEncode($htmlString, $flags = ENT_XML1)
    {
        for($i = 0 ; $i < 2 ; $i++) {
            $htmlString = htmlentities($htmlString, $flags);
        }
        return $htmlString;
    }
}