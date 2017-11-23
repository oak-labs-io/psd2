<?php

namespace OakLabs\Psd2\Tests;

use OakLabs\Psd2\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testInstantiation()
    {
        $data = [
            'external_uid' => '1234567890',
            'account_uid' => '12345',
            'transaction_uid' => 'abcd510',
            'amount' => 10,
            'iban' => 'DE0000000000000000',
            'bic' => 'ABCDEFGH',
            'description' => 'My Description',
            'created_at' => '2017-11-23 12:00:00'
        ];

        $transaction = new Transaction($data);

        $this->assertEquals($data['external_uid'], $transaction->getExternalUid());
        $this->assertEquals($data['account_uid'], $transaction->getAccountUid());
        $this->assertEquals($data['transaction_uid'], $transaction->getTransactionUid());
        $this->assertEquals($data['amount'], $transaction->getAmount());
        $this->assertEquals($data['iban'], $transaction->getIban());
        $this->assertEquals($data['bic'], $transaction->getBic());
        $this->assertEquals($data['description'], $transaction->getDescription());
        $this->assertEquals($data['created_at'], $transaction->getCreatedAt());
    }

    /**
     * @expectedException PHPUnit\Framework\Error\Error
     */
    public function testInstantiationWithEmptyData()
    {
        $data = [];

        new Transaction($data);
    }
}
