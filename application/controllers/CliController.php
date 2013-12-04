<?php
namespace Controllers;
use Libraries\TinyPHP\ControllerBase;
class CliController extends ControllerBase
{
    public function SampleCliFunction()
    {
        // perform some logic that you want to run from the command line
        echo "Hello From the Command Line!\r\n";
    }
}