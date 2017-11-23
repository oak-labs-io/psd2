<?php

namespace OakLabs\Psd2;

class AccountDetail
{
    /**
     * @var string
     */
    protected $accountNumber;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @var int|null
     */
    protected $balance;

    /**
     * @var int|null
     */
    protected $balanceAvailable;

    /**
     * @var string
     */
    protected $createdAt;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var array|null
     */
    protected $customers;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $id;

    public function __construct(array $data, array $customers = null)
    {
        $this->accountNumber = $data['account_number'];

        $this->bic = $data['bic'] ?? null;

        $this->balance = $data['balance'] ?? null;

        $this->balanceAvailable = $data['balance_available'] ?? null;

        $this->createdAt = $data['created_at'];

        $this->currency = $data['currency'] ?? null;

        $this->iban = $data['iban'];

        $this->id = $data['id'];

        $this->customers = $customers;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return (string)$this->accountNumber;
    }

    /**
     * @return null|string
     */
    public function getBic():? string
    {
        return $this->bic;
    }

    /**
     * @return int|null
     */
    public function getBalance():? int
    {
        return $this->balance;
    }

    /**
     * @return int|null
     */
    public function getBalanceAvailable():? int
    {
        return $this->balanceAvailable;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return null|string
     */
    public function getCurrency():? string
    {
        return $this->currency;
    }

    /**
     * @return array|null
     */
    public function getCustomers():? array
    {
        return $this->customers;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string)$this->id;
    }
}
