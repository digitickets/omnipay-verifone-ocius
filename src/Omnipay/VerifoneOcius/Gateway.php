<?php
namespace Omnipay\VerifoneOcius;

use SimpleXMLElement;
use Omnipay\VerifoneOcius\Message\CompletePurchaseRequest;
use Omnipay\VerifoneOcius\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;
/**
 * Verifone Gateway
 *
 * @link http://www.verifone.co.uk/solutions-services/paas/payware-ocius/customer-not-present/
 */
class Gateway extends AbstractGateway
{
    protected $livePaymentPageUrl = 'TBA';
    protected $testPaymentPageUrl = 'https://paypage2-test.cxmlpg.com/paypage.aspx';
    public function getName()
    {
        return 'Verifone';
    }
    public function getDefaultParameters()
    {
        return array(
            'accountId' => '',
            'merchantId' => '',
            'systemGuid' => ''
        );
    }
//    public function getPassword()
//    {
//        return $this->getParameter('password');
//    }
//    public function setPassword($value)
//    {
//        return $this->setParameter('password', $value);
//    }
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
    
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\VerifoneOcius\Message\PurchaseRequest', $parameters);
    }
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\VerifoneOcius\Message\CompletePurchaseRequest', $parameters);
    }
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\VerifoneOcius\Message\RefundRequest', $parameters);
    }
    
    public function PaymentPageUrl() {
        return $this->getTestMode() ? $this->testPaymentPageUrl : $this->livePaymentPageUrl;
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
        $postDataXml->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $postDataXml->addAttribute('xmlns:xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        
        $postDataXml->addChild('api', 2);
        $postDataXml->addChild('merchantid', $this->getMerchantId());
        $postDataXml->addChild('requesttype', 'eftrequest');
        $postDataXml->addChild('requestdata', $this->getRequestdataInputValue());
        $postDataXml->addChild('keyname');
        
        $postData = htmlentities(htmlentities($postDataXml->asXML()));
        
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
        $requestDataXml->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $requestDataXml->addAttribute('xmlns:xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        
        $requestDataXml->addChild('accountid', $this->getAccountId());
        $requestDataXml->addChild('allowedpaymentmethods', '1');

        $merchantXml = $requestDataXml->addChild('merchant');
        $merchantXml->addChild('merchantid', $this->getMerchantId());
        $merchantXml->addChild('systemguid', $this->getSystemGuid());

        $requestDataXml->addChild('merchantreference', 'xxx'); // TBA
        $requestDataXml->addChild('returnurl', 'xxx'); // TBA
        $requestDataXml->addChild('template', '');
        $requestDataXml->addChild('capturemethod', '12');

        $customerXml = $requestDataXml->addChild('customer', '');
        // TBA - address
        // TBA - basket
        // TBA - deliveryaddress
        $customerXml->addChild('deliveryedit', 'false');
        $customerXml->addChild('email', 'xxx@xxx.co.uk');
        $customerXml->addChild('firstname', 'Xxx');
        $customerXml->addChild('lastname', 'xx');

        $requestDataXml->addChild('processingidentifier', '1');
        $requestDataXml->addChild('registertoken', 'false');
        $requestDataXml->addChild('showorderconfirmation', 'false');
        $requestDataXml->addChild('transactionvalue', '99.99'); // TBA
        
        return $requestDataXml->asXML();
        
    }
}