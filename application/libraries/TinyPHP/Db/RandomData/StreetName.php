<?php
namespace Libraries\TinyPHP\Db\RandomData;
class StreetName
{
    private $_id;
    private $_streetName;

    public function getId()
    {
	return $this->_id;
    }

    public function setId($_id)
    {
	$this->_id = $_id;
	return $this;
    }

    public function getStreetName()
    {
	return $this->_streetName;
    }

    public function setStreetName($_streetName)
    {
	$this->_streetName = $_streetName;
	return $this;
    }

}