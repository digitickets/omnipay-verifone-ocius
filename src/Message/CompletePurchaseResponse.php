<?php

namespace Pedanticantic\VerifoneOcius\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Verifone Ocius Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['message']) && $this->data['message'] == 'Success';
    }

    public function isRedirect()
    {
        return false;
    }

    /**
     * Method to return the Code, which is the txnresult.
     * @return mixed|null
     */
    public function getCode()
    {
        return isset($this->data['txnresult']) ? $this->data['txnresult'] : null;
    }

    /**
     * Method to return the Message, which is the authmessage.
     * @return mixed|null
     */
    public function getMessage()
    {
        return isset($this->data['authmessage']) ? $this->data['authmessage'] : null;
    }

    /**
     * Method to return the TransactionReference, which is the transactionid in Verifone (confusing!).
     * @return mixed|null
     */
    public function getTransactionReference()
    {
        return isset($this->data['transactionid']) ? $this->data['transactionid'] : null;
    }

    /**
     * Method to return the MerchantReference (named for convention with Omnipay abstract class)
     * @return mixed|null
     */
    public function getTransactionId()
    {
        return isset($this->data['merchantreference']) ? $this->data['merchantreference'] : null;
    }

    /**
     * Method to return the AuthCode, which is the authcode.
     * @return mixed|null
     */
    public function getAuthCode()
    {
        return isset($this->data['authcode']) ? $this->data['authcode'] : null;
    }
}
