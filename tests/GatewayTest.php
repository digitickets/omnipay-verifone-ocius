<?php

namespace Omnipay\VerifoneOcius;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testStub()
    {
        $this->setMockHttpResponse('DirectPurchaseSuccess.txt');

        $this->assertSame(1, 1);
    }
}
