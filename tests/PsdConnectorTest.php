<?php

namespace OakLabs\Psd2\Tests;

use OakLabs\Psd2\Authorization\Authorization;
use OakLabs\Psd2\Connector;
use OakLabs\Psd2\Gateway\FidorGateway;
use PHPUnit\Framework\TestCase;

class PsdConnectorTest extends TestCase
{
    public function testFidorInstantiation()
    {
        $bankName = 'fidor';

        $authorization = new Authorization([
            'code' => '1234567890',
            'state' => 'state',
            'redirect_uri' => 'http://somecallback',
            'client_id' => 'app_id',
            'client_secret' => 'secret'
        ]);

        $connector = (new Connector($authorization));
        $gateway = $connector->getBankGateway($bankName, true);

        $this->assertInstanceOf(Connector::class, $connector);
        $this->assertInstanceOf(FidorGateway::class, $gateway);
    }

    /**
     * @expectedException OakLabs\Psd2\UnsupportedBankException
     */
    public function testFidorInstantiationUnsupportedBank()
    {
        $bankName = 'sparkasse';

        $authorization = new Authorization([
            'code' => '1234567890',
            'state' => 'state',
            'redirect_uri' => 'http://somecallback',
            'client_id' => 'app_id',
            'client_secret' => 'secret'
        ]);

        $connector = (new Connector($authorization));
        $connector->getBankGateway($bankName, true);
    }
}
