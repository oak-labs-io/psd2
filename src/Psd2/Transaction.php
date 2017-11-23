<?php

namespace OakLabs\Psd2;

class Transaction
{
    /**
     * @var string
     */
    protected $externalUid;

    /**
     * @var string
     */
    protected $accountUid;

    /**
     * @var string|null
     */
    protected $transactionUid;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $createdAt;

    public function __construct(array $data)
    {
        $this->externalUid = $data['external_uid'];

        $this->accountUid = $data['account_uid'];

        $this->transactionUid = $data['transaction_uid'] ?? null;

        $this->amount = $data['amount'];

        $this->iban = $data['iban'];

        $this->bic = $data['bic'] ?? null;

        $this->description = $data['description'] ?? null;

        $this->createdAt = $data['created_at'];
    }

    /**
     * @return string
     */
    public function getExternalUid(): string
    {
        return $this->externalUid;
    }

    /**
     * @return string
     */
    public function getAccountUid(): string
    {
        return $this->accountUid;
    }

    /**
     * @return string|null
     */
    public function getTransactionUid():? string
    {
        return $this->transactionUid;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @return string|null
     */
    public function getBic():? string
    {
        return $this->bic;
    }

    /**
     * @return string|null
     */
    public function getDescription():? string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
