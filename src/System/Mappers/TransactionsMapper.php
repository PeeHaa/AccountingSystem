<?php
namespace AccountingSystem\System\Mappers;

use mysqli;

class TransactionsMapper
{
    public $errors = [];
    public $messages = [];
    private $database;
    private $ledgerMapper;

    public function __construct(mysqli $database, LedgerMapper $ledgerMapper)
    {
        $this->database = $database;
        $this->ledgerMapper = $ledgerMapper;
    }

    public function validateTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount)
    {
        $this->errors = [];

        if (!$this->ledgerMapper->accountExists($debitAccountID)) {
            $this->errors[] = "Debit Entry Account doesn't exist!";
        }
        if (!$this->ledgerMapper->accountExists($creditAccountID)) {
            $this->errors[] = "Credit Entry Account doesn't exist!";
        }
        if (strlen($debitTransactionDescription) < 3 || strlen($debitTransactionDescription) > 20) {
            $this->errors[] = "Debit Transaction Description length should be between 3 to 20 characters inclusively.";
        }
        if (strlen($creditTransactionDescription) < 3 || strlen($creditTransactionDescription) > 20) {
            $this->errors[] = "Credit Transaction Description length should be between 3 to 20 characters inclusively.";
        }
        if (empty($amount)) {
            $this->errors[] = "Please enter the Debit and Credit amount!";
        }
        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    public function insertTransaction($debitAccountID, $creditAccountID, $debitTransactionDescription, $creditTransactionDescription, $amount)
    {
        $this->messages = [];

        $date = time();

        $query = $this->database->prepare("INSERT INTO transactions VALUES('', ?, ?, ?, ?, ?, ?);");
        $query->bind_param('ssssss', $debitTransactionDescription, $amount, $debitAccountID, $creditAccountID, 'Debit', $date);
        $query->execute();
        $query->close();

        $query = $this->database->prepare("INSERT INTO transactions VALUES('', ?, ?, ?, ?, ?, ?);");
        $query->bind_param('ssssss', $creditTransactionDescription, $amount, $creditAccountID, $debitAccountID, 'Credit', $date);
        $query->execute();
        $query->close();

        $this->messages[] = "Transaction logged successfully!";
        return true;
    }

    public function deleteTransaction($transactionID)
    {
        $this->messages = [];

        $query = $this->database->prepare("SELECT transactionDate FROM transactions WHERE id=?");
        $query->bind_param('s', $transactionID);
        $query->execute();
        $query->bind_result($transactionDate);
        $query->store_result();
        while ($query->fetch()) {
            $queryTwo = $this->database->prepare("DELETE FROM transactions WHERE transactionDate=?");
            $queryTwo->bind_param('s', $transactionDate);
            $queryTwo->execute();
            $queryTwo->close();
        }
        $query->free_result();
        $query->close();

        $this->messages[] = "Transaction reversed successfully!";

        return true;
    }

    public function reverseAccountTransactions($accountID)
    {
        if ($this->ledgerMapper->accountExists($accountID)) {
            $query = $this->database->prepare("DELETE FROM transactions WHERE transactionAccountID=? OR transactionOppositeAccountID=?");
            $query->bind_param('ss', $accountID, $accountID);
            $query->execute();
            $query->close();
            return true;
        }
        return false;
    }

    public function transactionExists($transactionID)
    {
        $this->errors = [];

        $query = $this->database->prepare("SELECT * FROM transactions WHERE id=?");
        $query->bind_param('s', $transactionID);
        $query->execute();
        $query->store_result();
        $numOfResults = $query->num_rows;
        $query->free_result();
        $query->close();

        if ($numOfResults > 0) {
            return true;
        }

        $this->errors[] = "Transaction doesn't exist!";
        return false;
    }

    public function getTransactionsForAccount($accountID)
    {
        $transactionData = [];
        $iterator = 0;

        $query = $this->database->prepare("SELECT id, transactionDescription, transactionAmount, transactionEntrySide, transactionDate FROM transactions WHERE transactionAccountID=?");
        $query->bind_param('s', $accountID);
        $query->execute();
        $query->bind_result($id, $description, $amount, $side, $date);
        $query->store_result();
        while ($query->fetch()) {
            $transactionData[$iterator]['id'] = $id;
            $transactionData[$iterator]['description'] = $description;
            $transactionData[$iterator]['amount'] = $amount;
            $transactionData[$iterator]['side'] = $side;
            $transactionData[$iterator]['date'] = gmdate('d/m/Y', $date);
            $iterator++;
        }
        $query->free_result();
        $query->close();
        return $transactionData;
    }
} 