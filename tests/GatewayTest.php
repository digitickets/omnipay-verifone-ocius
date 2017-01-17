<?php

namespace Digitickets\VerifoneOcius;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testStub()
    {
        $this->assertSame(1, 1);
    }
}
