<?php
namespace Libraries\TinyPHP\Db;
use Libraries\TinyPHP\Application;
use \PDO;
class Adapter
{
    private static $pdoInstance = null;

    private function __construct(){}

    public static function GetMysqlAdapter(){
        if(!self::$pdoInstance){
            $conf = Application::$config;
            $dbhost = isset($conf['db_host']) ? $conf['db_host'] : '';
            $dbuser = isset($conf['db_username']) ? $conf['db_username'] : '';
            $dbpass = isset($conf['db_password']) ? $conf['db_password'] : '';
            $dbname = isset($conf['db_name']) ? $conf['db_name'] : '';
            $dsn = 'mysql:host=' . $dbhost . ';dbname=' . $dbname;
            $oPDO = new PDO($dsn,$dbuser,$dbpass);
            $oPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $oPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
            self::$pdoInstance = $oPDO;
        }
        return self::$pdoInstance;
    }
	
}