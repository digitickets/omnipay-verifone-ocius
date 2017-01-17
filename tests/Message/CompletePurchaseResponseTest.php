<?php

namespace Digitickets\VerifoneOcius\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $requestData = array(
            'messagetype'             => 'Txn_Response',
            'timestamp'               => '2015-02-20T17:00:00:00',
            'message'                 => 'Success',
            'apiversion'              => '2',
            'merchantreference'       => 'D1234567',
            'transactioncurrencycode' => 'GBP',
            'txnvalue'                => '10.00',
            'pan'                     => '412345*****1234',
            'cpc'                     => 'False',
            'card_expiry'             => '0217',
            'pan_hash'                => 'ASDF1234',
            'tokenid'                 => '',
            'tokenexpiration'         => '',
            'transactionid'           => '111111',
            'resultdatetimestring'    => '2015-02-20T17:04:00:00',
            'authcode'                => '123456',
            'authmessage'             => 'OK',
            'messagenumber'           => '1',
            'txnresult'               => 'AUTHORISED',
            'ad1avs_result'           => '2',
            'pcavs_result'            => '2',
            'cvc_result'              => '2',
            'merchantnumber'          => '12345',
            'tid'                     => '999999',
            'schemename'              => '2',
            'errordescription'        => '',
            'errorcode'               => '',
            'rejectcode'              => '',
            'resultcode'              => '0',
            'enrolled'                => 'Y',
            'authenticationstatus'    => 'Y',
            'atsdata'                 => '',
            'authenticationcavv'      => '',
            'authenticationeci'       => '',
            'authenticationtime'      => '',
            'payerauthrequestid'      => '',
            'result'                  => '',
            'signature'               => base64_decode('VCFDmL2sbvU0Hujp5Pdg+H8pGcroUOtCqa/Q7T7rIvy4Ed5KtaFMuSeEpUmaK6DYwW4wTQVHxvkISkEQbtXQq93+Q6qBuTGGT1DouWQR7OnigqljgI85EwbBpl6113bNNIRLkZ5pp04xj5Sc/Mr4jhBoMeSgQsD3KmDOW9zAHrA='),
            'customerspecifichash'    => ''
        );
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            $requestData
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('111111', $response->getTransactionReference());
    }
}
