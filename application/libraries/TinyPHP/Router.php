<?php
namespace Libraries\TinyPHP;
use Controllers\ErrorController;
class Router
{

	private $customRoutes = array();

	public function __construct(array $customRoutes)
	{
		$this->customRoutes = $customRoutes;
	}

	public function dispatch($requestURI)
	{
		self::parseUriString($requestURI);
		
		$routeFound = false;

		// 1st, see if this $requestURI is a customRoute
		if(!empty($this->customRoutes)){
			foreach($this->customRoutes as $customRoute){
				if($customRoute->getCustomUrl() == $requestURI){
					self::createControllerInstance($customRoute);
					$routeFound = true;
					break;
				}
			}
		}

		$route = new Route();

		// if not found in customRoutes, try default route.
		if(!$routeFound){
			$uriPathParts = explode("/",$requestURI);
			$controllerPart = $uriPathParts[0];
			if(strpos($controllerPart,"-") !== false){
				$controllerNameParts = explode("-",$controllerPart);
				$controllerName = '';
				foreach($controllerNameParts as $k => $namePart){
					$controllerName .= ucfirst($namePart);
					if($k + 1 == count($controllerNameParts)){
						$controllerName .= "Controller";
					}
				}
			}else{
				$controllerName = ucfirst($controllerPart) . "Controller";
			}
			$route->setController($controllerName);

			if(isset($uriPathParts[1])){
				$functionName = $uriPathParts[1];
				if(strpos($functionName,"-") !== false){
					$functionNameParts = explode("-",$functionName);
					$functionName = '';
					foreach($functionNameParts as $k => $namePart){
						if(!$k){
							$functionName .= $namePart;
						}else{
							$functionName .= ucfirst($namePart);
						}
					}
				}
				$route->setFunc($functionName);
			}
			if(self::createControllerInstance($route)){
				$routeFound = true;
			}
		}

		// custom route not found & controller / function not found for default route.
		if(!$routeFound){
			$route->setController('ErrorController');
			$route->setFunc('errorPage');
			self::createControllerInstance($route);
		}
	}

	private static function parseUriString(&$requestURI)
	{
		$aURIParts = parse_url($requestURI);
		$requestURI = $aURIParts['path'];
		if(substr($requestURI,-1) == '/'){
			$requestURI = substr($requestURI,0,-1);
		}
		if(substr($requestURI,0,1) == '/'){
			$requestURI = substr($requestURI,1);
		}
		if(!trim($requestURI)){
			$requestURI = 'index';
		}
	}

	private static function createControllerInstance(Route $route)
	{
		$controllerName = 'Controllers\\' . $route->getController();
		$functionName = $route->getFunc();
		if(!class_exists($controllerName)){
			return false;
		}
		if(!method_exists($controllerName,$functionName)){
			return false;
		}
		new $controllerName($functionName);
		return true;
	}

}

?>