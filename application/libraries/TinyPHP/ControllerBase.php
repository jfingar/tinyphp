<?php
namespace Libraries\TinyPHP;
use Controllers\LayoutController;
abstract class ControllerBase
{
	
    protected $view;
    protected $layout = 'layout';
    protected $isAjax = false;
    protected $suppressView = false;
    protected $suppressLayout = false;
    protected $scripts = array();
    protected $stylesheets = array();
    protected $_rawPostData = '';
    private $functionName;
    private $content;
	
    public function __construct($func,$isCli = false)
    {
        $bootstrap = new \Bootstrap($this);
        $bootstrap->run();
        $this->_rawPostData = file_get_contents("php://input");
        $this->init();
        if(method_exists($this,$func)){
            $this->functionName = $func;
            $this->$func();
        }else{
            if($isCli){
                $exceptionMsg = "That function (" . $func . ") doesn't exist in CliContoller.";
            }else{
                $exceptionMsg = "The function specified for this Route (" . $func . ") does not exist. Make sure you create the function in the controller for this page.";
            }
            throw new \Exception($exceptionMsg);
        }
        if(!$isCli){
            if($this->isAjax){
                $this->suppressLayout = true;
                $this->suppressView = true;
            }
            if(!$this->suppressLayout){
                $layout = new LayoutController();
                $layout->setGlobalVars();
            }
            $this->renderSite();
        }
    }
	
    private function renderSite()
    {
        $output = '';
        if(!$this->suppressView){
            if(!$this->view){
                $controllerName = get_class($this);
                if(strpos($controllerName,'\\') !== false){
                    $classNameParts = explode('\\',$controllerName);
                    $controllerName = end($classNameParts);
                }
                $folderName = strtolower(preg_replace('/(?<!^)([A-Z])/','-\\1',substr($controllerName,0,-10)));
                $functionName = strtolower(preg_replace('/(?<!^)([A-Z])/','-\\1',$this->functionName));
                $this->view = 'pages/' . $folderName . '/' . $functionName;
            }
            $this->content = $this->returnView($this->view);
            $output = $this->content;
        }
        if(!$this->suppressLayout){
            ob_start();
            include_once APPLICATION::$VIEW_DIR . 'layouts/' . $this->layout . '.php';
            $output = ob_get_contents();
            ob_end_clean();
        }
        echo $output;
    }
	
    protected function returnView($pathToView)
    {
        ob_start();
        if(file_exists(APPLICATION::$VIEW_DIR . $pathToView . '.php')){
            include APPLICATION::$VIEW_DIR . $pathToView . '.php';
        }else{
            echo "TinyPHP Framework is trying to render a view file that doesn't exist: " . $pathToView . ".php - please create it, or set \$this->view to a view file that does exist.";
        }
        $view = ob_get_contents();
        ob_end_clean();
        return $view;
    }
	
    protected function init()
    {

    }
	
    protected function index()
    {

    }
	
    protected function getJavascripts()
    {
        $scriptsString = '';
        if(!empty($this->scripts)){
            foreach($this->scripts as $scriptSrc){
                $scriptsString .= "<script type=\"text/javascript\" src=\"" . $scriptSrc . "\"></script>\r\n";
            }
        }
        return $scriptsString;
    }
	
    protected function getStylesheets()
    {
        $stylesheetsString = '';
        if(!empty($this->stylesheets)){
            foreach($this->stylesheets as $stylesheet){
                $stylesheetsString .= "<link href=\"" . $stylesheet . "\" rel=\"stylesheet\" type=\"text/css\" />\r\n";
            }
        }
        return $stylesheetsString;
    }
    
    protected function getParams($type = 'POST', $allowed = array())
    {
        $type = strtoupper($type);
        switch($type){
            case 'POST' :
                $params = $_POST;
            break;
            case 'GET' :
                $params = $_GET;
            break;
            case 'REQUEST' :
                $params = $_REQUEST;
            break;
            default :
                $params = array();
        }
        
        if(!empty($allowed)){
            $this->sanitizeParams($allowed, $params);
        }
        
        return $params;
    }
    
    protected function sanitizeParams(array $allowed, &$params)
    {
        if(!empty($allowed)){
            foreach($allowed as $param){
                if(!isset($params[$param])){
                    $params[$param] = null;
                }
            }
            foreach($params as $key => $val){
                if(!in_array($key, $allowed)){
                    unset($params[$key]);
                }
            }
        }
    }
    
    protected function getRawPostParams($jsonDecode = false)
    {
        $params = $this->_rawPostData;
        if($jsonDecode){
            $params = json_decode($params);
            $params = (array) $params;
        }
        return $params;
    }
    
    protected function getParam($name, $default = '', $allowed = array())
    {
        $param = isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
        if(!$param && $default){
            $param = $default;
        }
        if(!empty($allowed)){
            if(!in_array($name, $allowed)){
                return false;
            }
        }
        return $param;
    }
    
    public function addStylesheet($link)
    {
        $this->stylesheets[] = $link;
    }
    
    public function addJavascript($link)
    {
        $this->scripts[] = $link;
    }
	
}