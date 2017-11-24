<?php

namespace OakLabs\Psd2\Tests;

use League\OAuth2\Client\Token\AccessToken;
use Mockery;
use OakLabs\Psd2\AccountDetail;
use OakLabs\Psd2\Authorization\Authorization;
use OakLabs\Psd2\Gateway\BankGatewayInterface;
use OakLabs\Psd2\Gateway\FidorGateway;
use OakLabs\Psd2\Transaction;
use PHPUnit\Framework\TestCase;

class FidorGatewayTest extends TestCase
{
    public function testRetrieveTokens()
    {
        $gateway = $this->createGatewayInstance();

        $responseMock = Mockery::mock(\GuzzleHttp\Psr7\Response::class);
        $responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn('{"access_token": "asoj23ou8asdij32"}');

        $clientMock = Mockery::mock(\GuzzleHttp\Client::class);
        $clientMock->shouldReceive('post')
            ->once()
            ->andReturn($responseMock);
        $gateway->setClient($clientMock);

        $tokens = $gateway->retrieveTokens();

        $this->assertInstanceOf(FidorGateway::class, $tokens);
        $this->assertInstanceOf(AccessToken::class, $gateway->getTokens());
    }

    public function testGetAccountDetails()
    {
        $accountDetails = ['data' => [
            [
                'account_number' => '1234567890',
                'bic' => 'ABCDEFGH',
                'balance' => 100,
                'balance_available' => 10,
                'created_at' => '2017-11-23 12:00:00',
                'currency' => 'EUR',
                'iban' => 'DE0000000000000000',
                'id' => '123'
            ]
        ]];

        $gateway = $this->createGatewayInstance();
        $gateway->shouldAllowMockingProtectedMethods();
        $gateway->shouldReceive('getAccessToken')->andReturn('asoj23ou8asdij32');

        $responseMock = Mockery::mock(\GuzzleHttp\Psr7\Response::class);
        $responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn(json_encode($accountDetails));

        $clientMock = Mockery::mock(\GuzzleHttp\Client::class);
        $clientMock->shouldReceive('get')
            ->once()
            ->andReturn($responseMock);
        $gateway->setClient($clientMock);

        $accounts = $gateway->getAccountDetails();

        $this->assertTrue(is_array($accounts));
        $this->assertInstanceOf(AccountDetail::class, $accounts[0]);
    }

    public function testGetSepaTransactions()
    {
        $transactions = ['data' => [
            [
                'external_uid' => '1234567890',
                'account_id' => '12345',
                'transaction_uid' => 'abcd510',
                'amount' => 10,
                'remote_iban' => 'DE0000000000000000',
                'bic' => 'ABCDEFGH',
                'subject' => 'My Description',
                'created_at' => '2017-11-23 12:00:00'
            ]
        ]];

        $gateway = $this->createGatewayInstance();
        $gateway->shouldAllowMockingProtectedMethods();
        $gateway->shouldReceive('getAccessToken')->andReturn('asoj23ou8asdij32');

        $responseMock = Mockery::mock(\GuzzleHttp\Psr7\Response::class);
        $responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn(json_encode($transactions));

        $clientMock = Mockery::mock(\GuzzleHttp\Client::class);
        $clientMock->shouldReceive('get')
            ->once()
            ->andReturn($responseMock);
        $gateway->setClient($clientMock);

        $transactions = $gateway->getSepaTransactions();

        $this->assertTrue(is_array($transactions));
        $this->assertInstanceOf(Transaction::class, $transactions[0]);
    }

    public function testCreateSepaTransaction()
    {
        $transactionInput = [
            'external_uid' => '1234567890',
            'account_id' => '12345',
            'transaction_uid' => 'abcd510',
            'amount' => 10,
            'remote_iban' => 'DE0000000000000000',
            'bic' => 'ABCDEFGH',
            'subject' => 'My Description',
            'created_at' => '2017-11-23 12:00:00'
        ];

        $transaction = [
            'external_uid' => '1234567890',
            'account_id' => '12345',
            'transaction_uid' => 'abcd510',
            'amount' => 10,
            'remote_iban' => 'DE0000000000000000',
            'bic' => 'ABCDEFGH',
            'subject' => 'My Description',
            'created_at' => '2017-11-23 12:00:00'
        ];

        $gateway = $this->createGatewayInstance();
        $gateway->shouldAllowMockingProtectedMethods();
        $gateway->shouldReceive('getAccessToken')->andReturn('asoj23ou8asdij32');

        $responseMock = Mockery::mock(\GuzzleHttp\Psr7\Response::class);
        $responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn(json_encode($transaction));

        $clientMock = Mockery::mock(\GuzzleHttp\Client::class);
        $clientMock->shouldReceive('post')
            ->once()
            ->andReturn($responseMock);
        $gateway->setClient($clientMock);

        $transaction = $gateway->createSepaTransaction($transactionInput);

        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    protected function createGatewayInstance(): BankGatewayInterface
    {
        $gateway = Mockery::mock(FidorGateway::class)->makePartial();
        $gateway->setAuthorization(new Authorization([
            'code' => '1234567890',
            'state' => '12345abcdefg67890hijklm',
            'redirect_uri' => 'http://someredirecturi.com',
            'client_id' => '123456',
            'client_secret' => 'abcdefghijklm1234567890',
        ]));

        return $gateway;
    }
}
