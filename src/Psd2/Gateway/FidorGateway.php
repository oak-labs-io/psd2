<?php

namespace OakLabs\Psd2\Gateway;

use League\OAuth2\Client\Token\AccessToken;
use OakLabs\Psd2\AccountDetail;
use OakLabs\Psd2\Transaction;

class FidorGateway extends AbstractBankGateway implements BankGatewayInterface
{
    protected const API_ACCESS_TOKEN = 'https://apm.fidor.de/oauth/token';
    protected const API_ACCESS_TOKEN_SANDBOX = 'https://aps.fidor.de/oauth/token';

    protected const API_ACCOUNT_DETAILS = 'https://apm.fidor.de/accounts';
    protected const API_ACCOUNT_DETAILS_SANDBOX = 'https://aps.fidor.de/accounts';

    protected const API_SEPA_TRANSACTIONS = 'https://apm.fidor.de/sepa_credit_transfers';
    protected const API_SEPA_TRANSACTIONS_SANDBOX = 'https://aps.fidor.de/sepa_credit_transfers';

    /**
     * @return $this
     */
    public function retrieveTokens()
    {
        $response = $this->client->post($this->getAccessTokenUrl(), [
            'headers' => [
                'Authorization' => 'Basic '
                    . base64_encode($this->authorization->getClientId()
                    . ':'
                    . $this->authorization->getClientSecret()),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'query' => [
                'client_id' => $this->authorization->getClientId(),
                'redirect_uri' => $this->authorization->getRedirectUri(),
                'code' => $this->authorization->getCode(),
                'grant_type' => 'authorization_code'
            ],
            'json' => []
        ]);

        $data = json_decode((string) $response->getBody(), true);

        $this->accessToken = new AccessToken($data);

        return $this;
    }

    /**
     * Retrieve and return the account details.
     *
     * @return AccountDetail[]
     */
    public function getAccountDetails(): array
    {
        $response = $this->client->get($this->getAccountDetailsUrl(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Accept' => 'application/vnd.fidor.de; version=1,text/json'
            ],
            'json' => []
        ]);

        $accountDetails = json_decode((string) $response->getBody(), true);

        return array_map(function ($account) {
            return new AccountDetail([
                'account_number' => $account['account_number'],
                'bic' => $account['bic'],
                'balance' => $account['balance'],
                'balance_available' => $account['balance_available'],
                'created_at' => $account['created_at'],
                'currency' => $account['currency'],
                'iban' => $account['iban'],
                'id' => $account['id'],
            ],
                $account['customers'] ?? null);
        },
            $accountDetails['data']);
    }

    /**
     * Retrieve and return the SEPA transactions.
     *
     * @param int $page
     * @param int $limit
     * @return Transaction[]
     */
    public function getSepaTransactions(int $page = 1, int $limit = 100): array
    {
        $response = $this->client->get($this->getSepaTransactionsUrl(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Accept' => 'application/vnd.fidor.de; version=1,text/json'
            ],
            'query' => [
                'page' => $page,
                'per_page' => $limit
            ],
            'json' => []
        ]);

        $sepaTransactions = json_decode((string) $response->getBody(), true);

        return array_map(function ($transaction) {
            return new Transaction([
                'external_uid' => $transaction['external_uid'],
                'account_uid' => $transaction['account_id'],
                'transaction_uid' => $transaction['transaction_id'] ?? null,
                'amount' => $transaction['amount'],
                'iban' => $transaction['remote_iban'],
                'bic' => $transaction['bic'] ?? null,
                'description' => $transaction['subject'] ?? null,
                'created_at' => $transaction['created_at']
            ]);
        },
            $sepaTransactions['data']);
    }

    /**
     * Create a SEPA transaction.
     *
     * @param array $data
     * @return Transaction
     */
    public function createSepaTransaction(array $data): Transaction
    {
        $response = $this->client->post($this->getSepaTransactionsUrl(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Accept' => 'application/vnd.fidor.de; version=1,text/json'
            ],
            'json' => $data
        ]);

        $transaction = json_decode((string) $response->getBody(), true);

        return new Transaction([
            'external_uid' => $transaction['external_uid'],
            'account_uid' => $transaction['account_id'],
            'transaction_uid' => $transaction['transaction_id'] ?? null,
            'amount' => $transaction['amount'],
            'iban' => $transaction['remote_iban'],
            'bic' => $transaction['bic'] ?? null,
            'description' => $transaction['subject'] ?? null,
            'created_at' => $transaction['created_at']
        ]);
    }
}
