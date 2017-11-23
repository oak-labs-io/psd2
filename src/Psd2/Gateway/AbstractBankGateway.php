<?php

namespace OakLabs\Psd2\Gateway;

use GuzzleHttp\Client as GuzzleHttp;
use GuzzleHttp\ClientInterface as GuzzleInterface;
use League\OAuth2\Client\Token\AccessToken;
use OakLabs\Psd2\Authorization\AuthorizationInterface;

abstract class AbstractBankGateway
{
    protected const API_ACCESS_TOKEN = '';
    protected const API_ACCESS_TOKEN_SANDBOX = '';

    protected const API_ACCOUNT_DETAILS = '';
    protected const API_ACCOUNT_DETAILS_SANDBOX = '';

    protected const API_SEPA_TRANSACTIONS = '';
    protected const API_SEPA_TRANSACTIONS_SANDBOX = '';

    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $useSandbox = true;

    public function __construct()
    {
        $this->setClient(new GuzzleHttp());
    }

    /**
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function setClient(GuzzleInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return bool
     */
    public function isUsingSandbox(): bool
    {
        return $this->useSandbox;
    }

    /**
     * Use the Sandbox environment.
     */
    public function setSandboxEnvironment()
    {
        $this->useSandbox = true;
    }

    /**
     * Use the Production environment.
     */
    public function unsetSandboxEnvironment()
    {
        $this->useSandbox = false;
    }

    /**
     * @param AuthorizationInterface $authorization
     */
    public function setAuthorization(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @param AccessToken|string $accessToken
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        if ($accessToken instanceof AccessToken) {
            $this->accessToken = $accessToken;
        } else {
            $this->accessToken = new AccessToken(['access_token' => $accessToken]);
        }

        return $this;
    }

    /**
     * @return AccessToken
     */
    public function getTokens(): AccessToken
    {
        return $this->accessToken;
    }

    protected function getAccessToken(): string
    {
        return $this->getTokens()->getToken();
    }

    protected function getAccessTokenUrl(): string
    {
        return $this->isUsingSandbox() ? static::API_ACCESS_TOKEN_SANDBOX : static::API_ACCESS_TOKEN;
    }

    protected function getAccountDetailsUrl(): string
    {
        return $this->isUsingSandbox() ? static::API_ACCOUNT_DETAILS_SANDBOX : static::API_ACCOUNT_DETAILS;
    }

    protected function getSepaTransactionsUrl(): string
    {
        return $this->isUsingSandbox() ? static::API_SEPA_TRANSACTIONS_SANDBOX : static::API_SEPA_TRANSACTIONS;
    }
}
