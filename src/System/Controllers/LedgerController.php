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

    public function balanceAllAccounts()
    {
        $accounts = $this->ledgerMapper->fetchAccountIDs();
        foreach ($accounts as $account) {
            if ($this->ledgerMapper->findAccountTotals($account) == true) {
                $this->ledgerMapper->balanceAccount($account);
            } else {
                $this->ledgerMapper->setErrorMessage("Unable to balance account of ID: ", $account);
            }
        }
        return true;
    }

    public function getErrors()
    {
        $errors = $this->ledgerMapper->errors;
        $this->ledgerMapper->errors = [];
        return $errors;
    }

    public function getMessages()
    {
        $messages = $this->ledgerMapper->messages;
        $this->ledgerMapper->messages = [];
        return $messages;
    }
} 