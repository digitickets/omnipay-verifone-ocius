<?php
namespace Omnipay\VerifoneOcius;

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
    public function getName()
    {
        return 'Verifone';
    }

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
    
    public function getDefaultParameters()
    {
        return array(
            'apiVersion' => '2',
            'allowedPaymentMethods' => '1',
            'showPaymentResult' => 'true',
            'captureMethod' => '12',
            'deliveryEdit' => 'false',
            'processingIdentifier' => '1',
            'registerToken' => 'false',
            'showOrderConfirmation' => 'true'
        );
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
}
