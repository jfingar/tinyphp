<?php
use Libraries\TinyPHP\Application;
use Controllers\CliController;
chdir("../../webroot");
require_once "../application/libraries/TinyPHP/Application.php";
Application::run('cli',true);

if(!isset($argv[1]) || !trim($argv[1])){
    die("Please provide the name of the function to call (from CliController) as argument #1\r\n");
}

try{
    new CliController($argv[1],true);
}catch(\Exception $e){
    echo $e->getMessage() . "\r\n";
}