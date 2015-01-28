<?php
namespace AccountingSystem\System\Controllers;

use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\System\Mappers\TransactionsMapper as TransactionsMapper;

class LedgerController
{
    private $ledgerMapper;
    private $transactionsMapper;

    public function __construct(LedgerMapper $ledgerMapper, TransactionsMapper $transactionsMapper)
    {
        $this->ledgerMapper = $ledgerMapper;
        $this->transactionsMapper = $transactionsMapper;
    }

    public function createAccount($accountName, $accountClass)
    {
        if ($this->ledgerMapper->validateAccountDetails($accountName, $accountClass) !== true) {
            return false;
        }
        $this->ledgerMapper->createAccount($accountName, $accountClass);
        return true;
    }

    public function editAccount($accountID, $accountName, $accountClass)
    {
        if ($this->ledgerMapper->validateAccountDetails($accountName, $accountClass) == true) {
            $this->ledgerMapper->editAccount($accountID, $accountName, $accountClass);
            return true;
        } else {
            return false;
        }
    }

    public function deleteAccount($accountID)
    {
        $this->transactionsMapper->reverseAccountTransactions($accountID);
        $this->ledgerMapper->deleteAccount($accountID);
        return true;
    }

    public function getAccountNamesAndBalances()
    {
        return $this->ledgerMapper->getAccountNamesAndBalances();
    }

    public function getAccountBalanceTotals()
    {
        $total['credit'] = $this->ledgerMapper->aggregateOfCreditBalances;
        $total['debit'] = $this->ledgerMapper->aggregateOfDebitBalances;
        return $total;
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

    public function getAccountNamesAndIDs()
    {
        return $this->ledgerMapper->fetchAccountNamesAndIDs();
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