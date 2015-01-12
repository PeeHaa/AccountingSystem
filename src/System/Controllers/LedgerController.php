<?php
namespace AccountingSystem\System\Controllers;

use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;

class LedgerController
{
    private $ledgerMapper;

    public function __construct(LedgerMapper $ledgerMapper)
    {
        $this->ledgerMapper = $ledgerMapper;
    }

    public function createAccount($accountName, $accountClass)
    {
        if ($this->ledgerMapper->validateNewAccountDetails($accountName, $accountClass) == true) {
            $this->ledgerMapper->createAccount($accountName, $accountClass);
            return true;
        } else {
            return false;
        }
    }

    public function getAccountNamesAndBalances()
    {
        $accountNamesAndBalances = $this->ledgerMapper->getAccountNamesAndBalances();
        return $accountNamesAndBalances;
    }
} 