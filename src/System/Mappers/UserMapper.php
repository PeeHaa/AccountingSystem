<?php

namespace AccountingSystem\System\Mappers;

use AccountingSystem\System\Entities\User as User;
use mysqli;

class UserMapper 
{
	public $errors = [];
    public $messages = [];
    private $database;
    private $user;

	public function __construct(mysqli $database, User $user)
	{
		$this->database = $database;
		$this->user = $user;
	}

	public function isValidLoginDetails($username, $password) 
	{
		$query = $this->database->prepare("SELECT password FROM users WHERE username=? LIMIT 1");
		$query->bind_param("s", $username);
		$query->execute();
		$query->bind_result($dbPassword);
        $query->store_result();
        $query->fetch();
		if($query->num_rows == 1 && password_verify($password, $dbPassword)) {
            return true;
		}
        return false;
		$query->free_result();
		$query->close();
	}

    public function login()
    {
        $_SESSION['username'] = $this->user->getUsername();
        $_SESSION['rank'] = $this->user->getRank();
        return true;
    }

    public function isValidRegistrationDetails($username, $password, $rank)
	{
		$this->errors = [];

		if(strlen($username) < 4)
			$this->errors[] = "Your username must be at least 3 characters long.";
		if(strlen($username) > 15)
			$this->errors[] = "Your username cannot be more than 15 characters long.";
		if(!ctype_alnum($username))
			$this->errors[] = "Your username can only contain alphabets and numbers.";
		if(strlen($password) < 6)
			$this->errors[] = "Your password must be at least 6 characters long.";
		if(strlen($password) > 255)
			$this->errors[] = "Your password cannot be more than 255 characters long.";
		if($rank != "User" || $rank != "Admin")
			$this->errors[] = "Invalid rank specified!";
		if(count($this->errors) > 0) {
			return false;
		} else {
			return true;
		}
	}

	public function register($username, $password, $rank)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = $this->database->prepare("INSERT INTO users VALUES('', ?, ?, ?);");
        $query->bind_param("sss", $username, $password, $rank);
		$query->execute();
		$query->close();
		return true;
	}

	public function isOnline()
	{
		if(isset($_SESSION['username']) && strlen($_SESSION['username']) > 0) {
			return true;
		} else {
			return false;
		}
	}

    public function logoutUser()
	{
        session_start();
		$_SESSION['username'] = null;
		$_SESSION['rank'] = null;
        session_destroy();
	}

    public function changePassword($oldPassword, $newPassword, $newPasswordConfirmation)
    {

        $this->errors = [];

        $this->fetchUserDetails($_SESSION['username']);

        if (password_verify($oldPassword, $this->user->getPassword()) == false) {
            $this->errors[] = "Old password entered was not correct!\n";
        }

        if (empty($newPassword) || empty($newPasswordConfirmation)) {
            $this->errors[] = "Your new password fields cannot be empty!\n";
        }

        if ($newPassword != $newPasswordConfirmation) {
            $this->errors[] = "Your new password field and new password confirmation field do not match!\n";
        }

        if (strlen($newPassword) < 6) {
            $this->errors[] = "Your new password should be at least 6 characters long\n";
        }

        if (strlen($newPassword) > 255) {
            $this->errors[] = "Your new password cannot be more than 255 characters long!\n";
        }

        if (count($this->errors) > 0) {
            return false;
        } else {
            $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $username = $this->user->getUsername();

            $query = $this->database->prepare("UPDATE users SET password=? WHERE username=?");
            $query->bind_param("ss", $newPassword, $username);
            $query->execute();
            $query->close();

            $this->user->setPassword($newPassword);

            $this->messages[] = "Password was successfully changed!";

            return true;
        }
    }

    public function fetchUserDetails($username)
	{
        $query = $this->database->prepare("SELECT id, username, password, rank FROM users WHERE username=? LIMIT 1");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($id, $username, $password, $rank);
        while ($query->fetch()) {
            $this->user->setID($id);
            $this->user->setUsername($username);
            $this->user->setPassword($password);
            $this->user->setRank($rank);
		}
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