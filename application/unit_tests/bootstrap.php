<?php
echo "CLI UNIT TESTS MUST BE RUN FROM THE application/unit_tests DIRECTORY!\r\n\r\n";
require_once '../libraries/TinyPHP/Application.php';
Libraries\TinyPHP\Application::$env = 'development';
Libraries\TinyPHP\Application::initAutoload('../');
Libraries\TinyPHP\Application::initConfig('../config/config.ini');