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
        $this->validate('accountId', 'merchantId', 'systemGuid', 'amount');

        $xmlDoc = $this->getOuterXml();
        // they really do want it double encoded!
        $xmlDoc = htmlentities($xmlDoc, ENT_QUOTES, 'UTF-8', true);
        $xmlDoc = htmlentities($xmlDoc, ENT_QUOTES, 'UTF-8', true);
        $data = array('postdata' => $xmlDoc);

        return $data;
    }

    /**
     * @param SimpleXMLElement $data
     *
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
    protected function getOuterXml()
    {
        // Build the post data, which contains the request data.
        $postDataXml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="utf-8"?><postdata/>'
        );
        $postDataXml->addAttribute(
            'xmlns:xmlns:xsd',
            'http://www.w3.org/2001/XMLSchema'
        );
        $postDataXml->addAttribute(
            'xmlns:xmlns:xsi',
            'http://www.w3.org/2001/XMLSchema-instance'
        );

        $postDataXml->api = $this->getApiVersion();
        $postDataXml->merchantid = $this->getMerchantId();
        $postDataXml->requesttype = 'eftrequest';
        $postDataXml->requestdata = $this->getInnerXml();
        $postDataXml->addChild('keyname');

        $postData = $postDataXml->asXML();

        return $postData;
    }

    /**
     * Method to return the request data for an eftrequest. It returns the data as XML, and it's
     * then embedded in the postdata data.
     *
     * @return string
     */
    protected function getInnerXml()
    {
        // Build the request data. This XML gets put into a single element
        // of the post data.
        $requestDataXml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="utf-8"?><eftrequest/>'
        );
        $requestDataXml->addAttribute(
            'xmlns:xmlns:xsd',
            'http://www.w3.org/2001/XMLSchema'
        );
        $requestDataXml->addAttribute(
            'xmlns:xmlns:xsi',
            'http://www.w3.org/2001/XMLSchema-instance'
        );

        $requestDataXml->accountid = $this->getAccountId();
        $requestDataXml->allowedpaymentmethods
            = $this->getAllowedPaymentMethods();
        $requestDataXml->showpaymentresult = $this->getShowPaymentResult();

        $merchantXml = $requestDataXml->addChild('merchant');
        $merchantXml->merchantid = $this->getMerchantId();
        $merchantXml->systemguid = $this->getSystemGuid();

        $requestDataXml->merchantreference = $this->getParameter(
            'transactionId'
        );
        $requestDataXml->returnurl = $this->getParameter('returnUrl');
        $requestDataXml->template = '';
        $requestDataXml->capturemethod = $this->getCaptureMethod();

        $customerXml = $requestDataXml->addChild('customer');
        $customerXml->deliveryedit = $this->getDeliveryEdit();
        $card = $this->getCard();
        if ($card) {
            $customerXml->email = $card->getEmail();
            $customerXml->firstname = $this->transliterate(
                $card->getFirstName()
            );
            $customerXml->lastname = $this->transliterate($card->getLastName());

            $billingAddressXml = $customerXml->addChild('address');
            $billingAddressXml->address1 = $this->transliterate(
                $card->getBillingAddress1()
            );
            $billingAddressXml->address2 = $this->transliterate(
                $card->getBillingAddress2()
            );
            $billingAddressXml->country = $this->transliterate(
                $card->getBillingCountry()
            );
            $billingAddressXml->postcode = $this->transliterate(
                $card->getBillingPostcode()
            );
            $billingAddressXml->town = $this->transliterate(
                $card->getBillingCity()
            );

            $deliveryAddressXml = $customerXml->addChild('deliveryaddress');
            $deliveryAddressXml->address1 = $this->transliterate(
                $card->getShippingAddress1()
            );
            $deliveryAddressXml->address2 = $this->transliterate(
                $card->getShippingAddress2()
            );
            $deliveryAddressXml->country = $this->transliterate(
                $card->getShippingCountry()
            );
            $deliveryAddressXml->postcode = $this->transliterate(
                $card->getShippingPostcode()
            );
            $deliveryAddressXml->town = $this->transliterate(
                $card->getShippingCity()
            );
        }
        $basketXml = $customerXml->addChild('basket');
        $basketXml->shippingamount = '0.00';
        $basketXml->totalamount = $this->getAmount();
        $basketXml->vatamount = '0.00';

        /**
         * @var \Omnipay\Common\Item $item
         */
        $basketItemsXml = $basketXml->addChild('basketitems');
        foreach ($this->getItems() as $item) {
            $basketItemXml = $basketItemsXml->addChild('basketitem');
            $basketItemXml->productname = $item->getName();
            $basketItemXml->quantity = $item->getQuantity();
            $basketItemXml->unitamount = $item->getPrice();
            $basketItemXml->vatamount = '0.00';
            $basketItemXml->vatrate = '0.00';
            $basketItemXml->lineamount = sprintf(
                '%0.2f',
                $item->getPrice() * $item->getQuantity()
            );
        }

        $requestDataXml->processingidentifier = $this->getProcessingIdentifier(
        );
        $requestDataXml->registertoken = $this->getRegisterToken();
        $requestDataXml->showorderconfirmation
            = $this->getShowOrderConfirmation();
        $requestDataXml->transactionvalue = $this->getAmount();

        return $requestDataXml->asXML();
    }

    /**
     * This gateway can only cope with basic ASCII values for things like customer
     * names and addresses. Therefore, this method exists to make sure UTF-8 characters
     * are converted to something appropriate.
     * Eg. "Card holder name value must consist of alphanumerics and the following characters only , . - \ / &"
     *
     * @param $string
     *
     * @return mixed
     */
    private function transliterate($string)
    {
        $string = transliterator_transliterate('Latin-ASCII;', $string);
        $string = preg_replace('/[^a-z0-9 \-&\.,]/i', '', $string);

        return $string;
    }
}
