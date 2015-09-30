<?php

namespace Omnipay\VerifoneOcius\Message;

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

    public function getTransactionReference()
    {
        return isset($this->data['merchantreference']) ? $this->data['merchantreference'] : null;
    }
}
