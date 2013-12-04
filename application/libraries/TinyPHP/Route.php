<?php
namespace Libraries\TinyPHP;
class Route
{
	private $_customUrl;
	private $_controller;
	private $_func = 'index';
	
	public function __construct($_customUrl = '',$_controller = '',$_func = '')
	{
		if($_customUrl){
			$this->_customUrl = $_customUrl;
		}
		if($_controller){
			$this->_controller = $_controller;
		}
		if($_func){
			$this->_func = $_func;
		}
	}

	public function setCustomUrl($_customUrl)
	{
		$this->_customUrl = $_customUrl;
	}

	public function getCustomUrl()
	{
		return $this->_customUrl;
	}

	public function setController($_controller)
	{
		$this->_controller = $_controller;
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function setFunc($_func)
	{
		$this->_func = $_func;
	}

	public function getFunc()
	{
		return $this->_func;
	}
}