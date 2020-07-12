<?php

namespace Grav\Plugin\YellDigital\Fio;

use h4kuna\Fio\FioRead;

class Account {
//    protected

    protected $id;

    protected $title;

    protected $accountId;

    protected $bankId;

    protected $currency;

    protected $iban;

    protected $bic;

    protected $balance;

    protected $openingBalance;

    protected $date_from;

    protected $date_to;

    //TODO implement movements
    protected $movements;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Account
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Account
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param mixed $accountId
     * @return Account
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * @param mixed $bankId
     * @return Account
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Account
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param mixed $iban
     * @return Account
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param mixed $bic
     * @return Account
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpeningBalance()
    {
        return $this->openingBalance;
    }

    /**
     * @param mixed $openingBalance
     * @return Account
     */
    public function setOpeningBalance($openingBalance)
    {
        $this->openingBalance = $openingBalance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFrom()
    {
        return $this->date_from;
    }

    /**
     * @param mixed $date_from
     * @return Account
     */
    public function setDateFrom($date_from)
    {
        $this->date_from = $date_from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTo()
    {
        return $this->date_to;
    }

    /**
     * @param mixed $date_to
     * @return Account
     */
    public function setDateTo($date_to)
    {
        $this->date_to = $date_to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMovements()
    {
        return $this->movements;
    }

    /**
     * @param mixed $movements
     * @return Account
     */
    public function setMovements($movements)
    {
        $this->movements = $movements;
        return $this;
    }



    public static function create(FioRead $fio, $id, $title = null) {
        $instance = new static();

        $instance->setId($id);
        $instance->setTitle($title);

        $movements = $fio->movements();
        $info = $movements->getInfo();

        $instance->setAccountId($info->accountId);
        $instance->setBankId($info->bankId);
        $instance->setIban($info->iban);
        $instance->setBic($info->bic);
        $instance->setCurrency($info->currency);
        $instance->setBalance($info->closingBalance);
        $instance->setOpeningBalance($info->openingBalance);

        return $instance;
    }
}
