<?php
namespace Libraries\TinyPHP\Db\RandomData;
class EmailDomain
{
    private $_id;
    private $_emailDomain;

    public function getId()
    {
	return $this->_id;
    }

    public function setId($_id)
    {
	$this->_id = $_id;
	return $this;
    }

    public function getEmailDomain()
    {
	return $this->_emailDomain;
    }

    public function setEmailDomain($_emailDomain)
    {
	$this->_emailDomain = $_emailDomain;
	return $this;
    }

}