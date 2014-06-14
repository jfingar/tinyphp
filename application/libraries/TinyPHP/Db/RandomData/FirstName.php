<?php
namespace Libraries\TinyPHP\Db\RandomData;
class FirstName
{
    private $_id;
    private $_firstName;

    public function getId()
    {
	return $this->_id;
    }

    public function setId($_id)
    {
	$this->_id = $_id;
	return $this;
    }

    public function getFirstName()
    {
	return $this->_firstName;
    }

    public function setFirstName($_firstName)
    {
	$this->_firstName = $_firstName;
	return $this;
    }

}