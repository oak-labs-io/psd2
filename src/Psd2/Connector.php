<?php

namespace OakLabs\Psd2;

use League\OAuth2\Client\Token\AccessToken;
use OakLabs\Psd2\Authorization\AuthorizationInterface;
use OakLabs\Psd2\Gateway\BankGatewayInterface;

class Connector
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    public function __construct(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @param BankGatewayInterface $bankGateway
     * @param bool $useSandbox
     *
     * @return BankGatewayInterface
     */
    public function getBankGateway(BankGatewayInterface $bankGateway, bool $useSandbox = true): BankGatewayInterface
    {
        $bankGateway->setAuthorization($this->authorization);

        if ($useSandbox === false) {
            $bankGateway->unsetSandboxEnvironment();
        } else {
            $bankGateway->setSandboxEnvironment();
        }

        return $bankGateway;
    }
}
