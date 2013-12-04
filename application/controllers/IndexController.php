<?php
namespace Controllers;
use Libraries\TinyPHP\ControllerBase;
class IndexController extends ControllerBase
{
    protected function index()
    {
        $this->title = "TinyPHP Skeleton App";
    }
}