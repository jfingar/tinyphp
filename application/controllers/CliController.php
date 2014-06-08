<?php
namespace Controllers;
use Libraries\TinyPHP\ControllerBase;
class CliController extends ControllerBase
{
    public function DataModel()
    {
        \Libraries\TinyPHP\Db\DataModeler::WriteModels('photo_cloud');
    }
}