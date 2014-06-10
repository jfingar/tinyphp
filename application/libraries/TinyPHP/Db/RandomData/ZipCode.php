<?php
namespace Libraries\TinyPHP\Db\RandomData;
class ZipCode
{
    private $_id;
    private $_city;
    private $_state;
    private $_zip;
    private $_latitude;
    private $_longitude;
    private $_county;

    public function getId()
    {
	return $this->_id;
    }

    public function setId($_id)
    {
	$this->_id = $_id;
	return $this;
    }

    public function getCity()
    {
	return $this->_city;
    }

    public function setCity($_city)
    {
	$this->_city = $_city;
	return $this;
    }

    public function getState()
    {
	return $this->_state;
    }

    public function setState($_state)
    {
	$this->_state = $_state;
	return $this;
    }

    public function getZip()
    {
	return $this->_zip;
    }

    public function setZip($_zip)
    {
	$this->_zip = $_zip;
	return $this;
    }

    public function getLatitude()
    {
	return $this->_latitude;
    }

    public function setLatitude($_latitude)
    {
	$this->_latitude = $_latitude;
	return $this;
    }

    public function getLongitude()
    {
	return $this->_longitude;
    }

    public function setLongitude($_longitude)
    {
	$this->_longitude = $_longitude;
	return $this;
    }

    public function getCounty()
    {
	return $this->_county;
    }

    public function setCounty($_county)
    {
	$this->_county = $_county;
	return $this;
    }

}