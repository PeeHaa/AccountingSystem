<?php

namespace AccountingSystem\System\Entities;

class User
{
	private $id;
	private $username;
	private $password;
	private $rank;

    public function getID()
    {
        return $this->id;
    }

	public function setID($id)
	{
        $this->id = $id;
	}

    public function getUsername()
	{
        return $this->username;
	}

	public function setUsername($username)
	{
        $this->username = $username;
	}

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRank()
    {
        return $this->rank;
    }

	public function setRank($rank)
	{
        $this->rank = $rank;
	}
}