<?php

namespace OakLabs\Psd2\Tests;

use OakLabs\Psd2\AccountDetail;
use PHPUnit\Framework\TestCase;

class AccountDetailTest extends TestCase
{
    public function testInstantiation()
    {
        $data = [
            'account_number' => '1234567890',
            'bic' => 'ABCDEFGH',
            'balance' => 100,
            'balance_available' => 10,
            'created_at' => '2017-11-23 12:00:00',
            'currency' => 'EUR',
            'iban' => 'DE0000000000000000',
            'id' => '123'
        ];

        $customers = ['1', '2'];

        $transaction = new AccountDetail($data, $customers);

        $this->assertEquals($data['account_number'], $transaction->getAccountNumber());
        $this->assertEquals($data['bic'], $transaction->getBic());
        $this->assertEquals($data['balance'], $transaction->getBalance());
        $this->assertEquals($data['balance_available'], $transaction->getBalanceAvailable());
        $this->assertEquals($data['created_at'], $transaction->getCreatedAt());
        $this->assertEquals($data['currency'], $transaction->getCurrency());
        $this->assertEquals($data['iban'], $transaction->getIban());
        $this->assertEquals($data['id'], $transaction->getId());
        $this->assertEquals($customers, $transaction->getCustomers());
        $this->assertEquals(count($customers), count($transaction->getCustomers()));
    }

    /**
     * @expectedException PHPUnit\Framework\Error\Error
     */
    public function testInstantiationWithEmptyData()
    {
        $data = [];

        new AccountDetail($data);
    }
}
