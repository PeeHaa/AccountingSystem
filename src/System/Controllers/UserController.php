<?php

namespace AccountingSystem\System\Controllers;

use AccountingSystem\System\Mappers\UserMapper as UserMapper;

class UserController 
{
	private $userMapper;

	public function __construct(UserMapper $userMapper)
	{
		$this->userMapper = $userMapper;
	}

	public function login($username, $password) 
	{
		if($this->userMapper->isValidLoginDetails($username, $password)) {
			$this->userMapper->fetchUserDetails($username);
			$this->userMapper->login();
			return true;
		} else {
			return false;
		}
	}

	public function register($username, $password, $rank)
	{
        if ($this->userMapper->isValidRegistrationDetails($username, $password, $rank)) {
			$this->userMapper->register($username, $password, $rank);
			return true;
		} else {
			return false;
		}
	}
}