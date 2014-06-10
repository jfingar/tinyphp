<?php
namespace Libraries\TinyPHP\Db\RandomData;
class LastName
{
    private $_id;
    private $_lastName;

    public function getId()
    {
	return $this->_id;
    }

    public function setId($_id)
    {
	$this->_id = $_id;
	return $this;
    }

    public function getLastName()
    {
	return $this->_lastName;
    }

    public function setLastName($_lastName)
    {
	$this->_lastName = $_lastName;
	return $this;
    }

}