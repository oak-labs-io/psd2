# Psd2

## Introduction

> Psd2 is a API client for Banks supporting PSD2 APIs with Oauth2 authentication.

PHP 7.1+ is required.

## Installation

Psd2 can be installed through Composer, just include `"oaklabs/psd2": "^1.0"` to your composer.json and run `composer update` or `composer install`.

## Supported Banks

 - [Fidor Bank](https://www.fidor.de/) ([API Documentation](https://api-docs.fidor.de))

## Usage

> Examples will be described using the Fidor Bank Gateway, but all Bank Gateways use the same methods.

Once we use fills in his/her own Bank details in the Bank OAuth2 screen, we must handle the callback to with the `state` and `code` variables in order to retrieve the Access Token and therefore being able to use the Bank API.

The `Connector` class is the class that will take care of instantiate the Bank Gateway.
Creating a Connector instance is the first step to use Psd2.

### Sandbox

All Bank Gateways can be used in a sandbox mode, which will make the Banks hit the sandbox API endpoints to allow testing.

### Retrieving the Access Token

```php
// Let's suppose we saved the state token in a $state variable,
// the random code in $code and we have a boolean $useSandbox variable

// First of all we need to create an Authorization instance

$authorization = new \OakLabs\Psd2\Authorization\Authorization([
    'code' => $code,
    'state' => $state,
    'redirect_uri' => 'the redirect_uri your set in your Bank API configuration,
    'client_id' => 'the client_id of the bank API,
    'client_secret' => 'the client secret of the bank API'
]);

// Let's now instanciate the Bank Gateway through the Connector
$tokens = (new Connector($authorization))
    ->getBankGateway(
        new \OakLabs\Psd2\Gateway\FidorGateway(),
        $useSandbox
    )
    ->retrieveTokens()
    ->getTokens();

// $tokens is now an instance of \League\OAuth2\Client\Token

$accessToken = $tokens->getToken();
$refreshToken = $tokens->getRefreshToken();
$expiration = $tokens->getExpires();
$hasExpired = $tokens->hasExpired();
$jsonSerialized = $tokens->jsonSerialize();
```

### Retrieving the Accounts

```php
// After we got the Access Token and we saved it in a $tokens variable
// we can interact with the Bank API

// In case of a new request, create again the Authorization instance,
// but this time we don't need state and code

$authorization = new Authorization([
    'redirect_uri' => 'the redirect_uri your set in your Bank API configuration,
    'client_id' => 'the client_id of the bank API,
    'client_secret' => 'the client secret of the bank API'
]);

$accounts = (new Connector($authorization))
    ->getBankGateway(
        new \OakLabs\Psd2\Gateway\FidorGateway(),
        $useSandbox
    )
    ->setAccessToken($accessToken)
    ->getAccountDetails();

// $accounts is an array of \OakLabs\Psd2\Psd\AccountDetail

foreach ($accounts as $account) {
    // $account->getAccountNumber()
    // $account->getBic()
    // $account->getBalance()
    // $account->getBalanceAvailable()
    // $account->getCreatedAt()
    // $account->getCurrency()
    // $account->getCustomers()
    // $account->getIban()
    // $account->getId()
}
```

### Retrieving SEPA Transactions

```php
// After we got the Access Token and we saved it in a $tokens variable
// we can interact with the Bank API

// In case of a new request, create again the Authorization instance,
// but this time we don't need state and code

$authorization = new Authorization([
    'redirect_uri' => 'the redirect_uri your set in your Bank API configuration,
    'client_id' => 'the client_id of the bank API,
    'client_secret' => 'the client secret of the bank API'
]);

// Let's now retrieve the SEPA Transactions using the API Pagination

$transactions = (new Connector($authorization))
    ->getBankGateway(
        new \OakLabs\Psd2\Gateway\FidorGateway(),
        $useSandbox
    )
    ->setAccessToken($accessToken)
    ->getSepaTransactions($page, $limit);

// $transactions is an array of \OakLabs\Psd2\Transaction

foreach ($transactions as $transaction) {
    // $transaction->getExternalUid()
    // $transaction->getAccountUid()
    // $transaction->getTransactionUid()
    // $transaction->getAmount()
    // $transaction->getIban()
    // $transaction->getBic()
    // $transaction->getDescription()
    // $transaction->getCreatedAt()
}
```

### Creating a SEPA Transaction

```php
// After we got the Access Token and we saved it in a $tokens variable
// we can interact with the Bank API

// In case of a new request, create again the Authorization instance,
// but this time we don't need state and code

$authorization = new Authorization([
    'redirect_uri' => 'the redirect_uri your set in your Bank API configuration,
    'client_id' => 'the client_id of the bank API,
    'client_secret' => 'the client secret of the bank API'
]);

// Let's suppose we have a $data array with the transaction we want to create
$data = [
    'external_uid' => '1234567890', // Some uid defined by us
    'account_id' => '12345', // The account_id comes from the Bank API and must be retrieved through getAccountDetails . It is NOT the account number
    'amount' => 10, // Amount of the transfer
    'remote_iban' => 'DE0000000000000000', // IBAN to transfer the money to
    'bic' => 'ABCDEFGH', // BIC
    'subject' => 'My Description' // Description
];

$transaction = (new Connector($authorization))
    ->getBankGateway(
        new \OakLabs\Psd2\Gateway\FidorGateway(),
        $useSandbox
    )
    ->setAccessToken($accessToken)
    ->createSepaTransaction($data);

// Transaction is an instance of \OakLabs\Psd2\Transaction

// $transaction->getExternalUid()
// $transaction->getAccountUid()
// $transaction->getTransactionUid()
// $transaction->getAmount()
// $transaction->getIban()
// $transaction->getBic()
// $transaction->getDescription()
// $transaction->getCreatedAt()
```

## Testing

Just call `vendor/bin/phpunit tests` to run the tests.

## Contribution guidelines

PSD2 follows PSR-1, PSR-2 and PSR-4 PHP coding standards, and semantic versioning.

Pull requests are welcome.

## License

PSD2 is free software distributed under the terms of the MIT license.
