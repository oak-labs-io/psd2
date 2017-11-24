<?php

namespace OakLabs\Psd2\Tests;

use OakLabs\Psd2\Authorization\Authorization;
use PHPUnit\Framework\TestCase;

class AuthorizationTest extends TestCase
{
    public function testInstantiation()
    {
        $data = [
            'code' => '1234567890',
            'state' => '12345abcdefg67890hijklm',
            'redirect_uri' => 'http://someredirecturi.com',
            'client_id' => '123456',
            'client_secret' => 'abcdefghijklm1234567890',
        ];

        $authorization = new Authorization($data);

        $this->assertEquals($data['code'], $authorization->getCode());
        $this->assertEquals($data['state'], $authorization->getState());
        $this->assertEquals($data['redirect_uri'], $authorization->getRedirectUri());
        $this->assertEquals($data['client_id'], $authorization->getClientId());
        $this->assertEquals($data['client_secret'], $authorization->getClientSecret());
    }

    /**
     * @expectedException PHPUnit\Framework\Error\Error
     */
    public function testInstantiationWithEmptyData()
    {
        $data = [];

        new Authorization($data);
    }
}
