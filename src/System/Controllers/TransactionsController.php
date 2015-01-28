<?php
namespace AccountingSystem\System\Controllers;

use AccountingSystem\System\Mappers\TransactionsMapper as TransactionsMapper;

class TransactionsController
{
    private $transactionsMapper;

    public function __construct(TransactionsMapper $transactionsMapper)
    {
        $this->transactionsMapper = $transactionsMapper;
    }

    public function createTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount)
    {
        if ($this->transactionsMapper->validateTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount)) {
            $this->transactionsMapper->insertTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount);
            return true;
        }
        return false;
    }

    public function deleteTransaction($transactionID)
    {
        if ($this->transactionsMapper->transactionExists($transactionID)) {
            $this->transactionsMapper->deleteTransaction($transactionID);
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