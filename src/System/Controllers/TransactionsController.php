<?php
namespace AccountingSystem\System\Controllers;

use AccountingSystem\System\Mappers\TransactionsMapper as TransactionsMapper;

class TransactionsController
{
    private $transactionsMapper;
    private $ledgerController;

    public function __construct(TransactionsMapper $transactionsMapper, LedgerController $ledgerController)
    {
        $this->transactionsMapper = $transactionsMapper;
        $this->ledgerController = $ledgerController;
    }

    public function createTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount)
    {
        if ($this->transactionsMapper->validateTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount)) {
            $this->transactionsMapper->insertTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount);
            $this->ledgerController->balanceAllAccounts();
            return true;
        }
        return false;
    }

    public function deleteTransaction($transactionID)
    {
        if ($this->transactionsMapper->transactionExists($transactionID)) {
            $this->transactionsMapper->deleteTransaction($transactionID);
            $this->ledgerController->balanceAllAccounts();
            return true;
        }
        return false;
    }

    public function getTransactionsForAccount($accountID)
    {
        return $this->transactionsMapper->getTransactionsForAccount($accountID);
    }

    public function getErrors()
    {
        $errors = $this->transactionsMapper->errors;
        $this->transactionsMapper->errors = [];
        return $errors;
    }

    public function getMessages()
    {
        $messages = $this->transactionsMapper->messages;
        $this->transactionsMapper->messages = [];
        return $messages;
    }
} 