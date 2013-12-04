<?php
namespace Controllers;
use Libraries\TinyPHP\ControllerBase;
use Models\Mappers\User AS User_Mapper;
use Models\Helpers\User AS User_Helper;
class ErrorController extends ControllerBase
{
    protected function init()
    {
        header(' ',true,404);
    }

    protected function errorPage(){
        $this->title = '404, Page Not Found!';
    }
}