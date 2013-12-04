<?php
namespace Libraries\TinyPHP;
class Debug
{
    public static function log($var,$file = 'log')
    {
        if(!$var){
            $var = 'null';
        }
        $output = print_r($var,true) . "\r\n";
        $fileHandle = fopen(Application::$LOG_DIR . $file,'a');
        fwrite($fileHandle,$output);
        fclose($fileHandle);
    }
}