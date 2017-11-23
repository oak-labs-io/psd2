<?php

namespace OakLabs\Psd2\Gateway;

use League\OAuth2\Client\Token\AccessToken;
use OakLabs\Psd2\AccountDetail;
use OakLabs\Psd2\Authorization\AuthorizationInterface;
use OakLabs\Psd2\Transaction;

interface BankGatewayInterface
{
    public function setSandboxEnvironment();

    public function unsetSandboxEnvironment();

    /**
     * @param AuthorizationInterface $authorization
     */
    public function setAuthorization(AuthorizationInterface $authorization);

    /**
     * @return $this
     */
    public function retrieveAuthotizationCode();

    /**
     * @param string|AccessToken $accessToken
     * @return $this
     */
    public function setAccessToken($accessToken);

    /**
     * @return $this
     */
    public function retrieveTokens();

    /**
     * @return AccessToken
     */
    public function getTokens(): AccessToken;

    /**
     * @return AccountDetail[]
     */
    public function getAccountDetails(): array;

    /**
     * @param int $page
     * @param int $limit
     * @return Transaction[]
     */
    public function getSepaTransactions(int $page = 1, int $limit = 100): array;

    /**
     * @param array $data
     * @return Transaction
     */
    public function createSepaTransaction(array $data): Transaction;
}
