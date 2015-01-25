<?php
namespace AccountingSystem\System\Mappers;

use mysqli;

class LedgerMapper
{
    public $errors = [];
    public $messages = [];
    private $database;
    private $accountClasses = ["NCA", "CA", "NCL", "CL", "E", "I", "S", "C"];
    private $aggregateOfDebitTransactions = 0;
    private $aggregateOfCreditTransactions = 0;

    private $accountTypeVerified;

    public function __construct(mysqli $database)
    {
        $this->database = $database;
    }

    public function validateNewAccountDetails($accountName, $accountClass)
    {
        $this->errors = [];

        if (strlen($accountName) < 3 || strlen($accountName) > 20) {
            $this->errors[] = "The account name can only be 3 to 20 characters in length!";
        }

        foreach ($this->accountClasses as $accountClassesArrayElement) {
            if ($accountClassesArrayElement == $accountClass) {
                $this->accountTypeVerified = true;
            }
        }

        if ($this->accountTypeVerified == false) {
            $this->errors[] = "Invalid account class specified!";
        }

        if (count($this->errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function createAccount($accountName, $accountClass)
    {
        $this->messages = [];

        $query = $this->database->prepare("INSERT INTO accounts VALUES('', ?, ?, '0', 'Balanced', '0');");
        $query->bind_param("ss", $accountName, $accountClass);
        $query->execute();
        $query->close();
        $this->messages[] = "Account '" . $accountName . "' of class '" . $accountClass . "' was successfully created!";
        return true;
    }

    public function getAccountNamesAndBalances()
    {
        $accountNamesAndBalances = [];
        $iterator = 0;

        $query = $this->database->prepare("SELECT accountName, accountBalance, accountBalanceSide FROM accounts");
        $query->execute();
        $query->bind_result($accountName, $accountBalance, $accountBalanceSide);
        $query->store_result();
        while ($query->fetch()) {
            $accountNamesAndBalances[$iterator]['accountName'] = $accountName;
            $accountNamesAndBalances[$iterator]['accountBalance'] = $accountBalance;
            $accountNamesAndBalances[$iterator]['accountBalanceSide'] = $accountBalanceSide;

            $iterator++;
        }
        $query->free_result();
        $query->close();

        return $accountNamesAndBalances;
    }

    public function accountExists($accountID)
    {
        $query = $this->database->prepare("SELECT * FROM accounts WHERE id=?");
        $query->bind_param("s", $accountID);
        $query->execute();
        $query->store_result();
        if ($query->num_rows == 1) {
            $query->close();
            return true;
        } else {
            $query->close();
            return false;
        }
    }

    public function fetchAccountIDs()
    {
        $accountIDs = [];

        $query = $this->database->prepare("SELECT id FROM accounts ORDER BY id ASC");
        $query->execute();
        $query->bind_result($id);
        while ($query->fetch()) {
            $accountIDs[] = $id;
        }
        $query->close();
        return $accountIDs;
    }

    public function findAccountTotals($accountID)
    {
        $this->errors = [];

        $query = $this->database->prepare("SELECT transactionAmount, transactionEntrySide FROM transactions WHERE transactionAccountID=?");
        $query->bind_param("s", $accountID);
        $query->execute();
        $query->bind_result($transactionAmount, $transactionEntrySide);
        $query->store_result();
        if ($query->num_rows > 0) {
            while ($query->fetch()) {
                if ($transactionEntrySide == "Debit") {
                    $this->aggregateOfDebitTransactions = $this->aggregateOfDebitTransactions + $transactionAmount;
                } else if ($transactionEntrySide == "Credit") {
                    $this->aggregateOfCreditTransactions = $this->aggregateOfCreditTransactions + $transactionAmount;
                }
                return true;
            }
        } else {
            $this->errors[] = "Unable to balance account of ID: " . $accountID;
            return false;
        }
        $query->free_result();
        $query->close();
    }

    public function balanceAccount($accountID)
    {
        $balanceEntrySide = null;
        $balance = 0;
        $total = 0;

        if ($this->aggregateOfDebitTransactions > $this->aggregateOfCreditTransactions) {
            $balanceEntrySide = "Credit";
            $balance = $this->aggregateOfDebitTransactions - $this->aggregateOfCreditTransactions;
            $total = $this->aggregateOfDebitTransactions;
        } else if ($this->aggregateOfCreditTransactions > $this->aggregateOfDebitTransactions) {
            $balanceEntrySide = "Debit";
            $balance = $this->aggregateOfCreditTransactions - $this->aggregateOfDebitTransactions;
            $total = $this->aggregateOfCreditTransactions;
        } else if ($this->aggregateOfDebitTransactions == $this->aggregateOfCreditTransactions) {
            $balanceEntrySide = "None";
            $balance = 0;
            $total = $this->aggregateOfDebitTransactions;
        }

        $query = $this->database->prepare("UPDATE accounts SET accountBalance=?, accountBalanceSide=?, accountTotal=? WHERE id=?");
        $query->bind_param("sss", $balance, $balanceEntrySide, $total, $accountID);
        $query->execute();
        $query->close();
        return true;
    }

    public function getErrors()
    {
        $errors = $this->errors;
        $this->errors = [];
        return $errors;
    }

    public function getMessages()
    {
        $messages = $this->messages;
        $this->messages = [];
        return $messages;
    }
} 