<?php
namespace Libraries\TinyPHP;
abstract class BootstrapBase
{
	protected $controller;
	
	public function __construct($controller)
	{
		$this->controller = $controller;
	}
	
	public function run()
	{
		$aBootstrapCalls = get_class_methods('Bootstrap');
		if(!empty($aBootstrapCalls))
		{
			foreach($aBootstrapCalls as $method)
			{
				if($method != 'run' && $method != '__construct')
				{
					$this->$method();
				}
			}
		}
	}
}