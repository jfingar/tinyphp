<?php
namespace Models;
class SampleModel
{
    private $_id;
    private $_property1;
    private $_property2;
    private $_property3;
    
    public function setId($val)
    {
        $this->_id = $val;
        return $this;
    }
	
    public function getId()
    {
        return $this->_id;
    }

    public function setProperty1($val)
    {
        $this->_property1 = $val;
        return $this;
    }

    public function getProperty1()
    {
        return $this->_property1;
    }
    
    public function setProperty2($val)
    {
        $this->_property2 = $val;
        return $this;
    }
    
    public function getProperty2()
    {
        return $this->_property2;
    }
    
    public function setProperty3($val)
    {
        $this->_property3 = $val;
        return $this;
    }
    
    public function getProperty3()
    {
        return $this->_property3;
    }
}