<?php
namespace AccountingSystem\System\Mappers;

use mysqli;

class LedgerMapper
{
    public $errors = [];
    public $messages = [];
    private $database;
    private $accountClasses = ["NCA", "CA", "NCL", "CL", "E", "I", "S", "C"];

    private $accountTypeVerified = false;

    public function __construct(mysqli $database)
    {
        $this->database = $database;
    }

    public function validateNewAccountDetails($accountName, $accountClass)
    {
        if (strlen($accountName) < 3 || strlen($accountName) > 20) {
            $this->errors[] = "The account name can only be 3 to 20 characters in length!";
        }

        foreach ($this->accountClasses as $accountClassesArrayElement) {
            if ($accountClassesArrayElement == $accountClass) {
                $this->accountTypeVerified = true;
            }
        }

        if ($this->accountTypeVerified == false) {
            $this->errors[] = "Invalid account class specified.";
        }

        if (count($this->errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function createAccount($accountName, $accountClass)
    {
        $query = $this->database->prepare("INSERT INTO accounts VALUES('', ?, ?, '0', 'Balanced', '0');");
        $query->bind_param("ss", $accountName, $accountClass);
        $query->execute();
        $query->close();
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