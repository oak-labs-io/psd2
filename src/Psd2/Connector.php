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
     * @param string $bankName
     * @param bool $useSandbox
     *
     * @return BankGatewayInterface
     */
    public function getBankGateway(string $bankName, bool $useSandbox = true): BankGatewayInterface
    {
        $bankGateway = $this->instantiateGateway($bankName);
        $bankGateway->setAuthorization($this->authorization);

        if ($useSandbox === false) {
            $bankGateway->unsetSandboxEnvironment();
        } else {
            $bankGateway->setSandboxEnvironment();
        }

        return $bankGateway;
    }

    /**
     * @param string $bankName
     * @return BankGatewayInterface
     */
    protected function instantiateGateway(string $bankName): BankGatewayInterface
    {
        switch (strtolower($bankName)) {
            case 'fidor':
                return new \OakLabs\Psd2\Gateway\FidorGateway();
                break;
            default:
                throw new UnsupportedBankException($bankName . ' is not supported yet');
        }
    }
}
