<?php

namespace Grav\Plugin\YellDigital\Fio;

class AccountsCollection implements \IteratorAggregate {

    protected $accounts;

    public function getIterator()
    {
        return new \ArrayIterator($this->accounts);
    }

    public function get(string $identifier)
    {
        if(!isset($this->accounts[$identifier]))
            return null;
        return $this->accounts[$identifier];
    }

    public function add(Account $account) {
        $this->accounts[$account->getId()] = $account;

        return $this;
    }
}
