<?php
namespace Libraries\TinyPHP\Db;
use Libraries\TinyPHP\Application;
class DataModeler
{
    private static $_schemaName;
    
    public static function WriteModels($schema_name, $tableName = null)
    {
        self::$_schemaName = $schema_name;
        $dbAdapter = Adapter::GetMysqlAdapter();
        $sql = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$schema_name'";
        if($tableName){
            $sql .= " AND TABLE_NAME = '$tableName'";
        }
        $statement = $dbAdapter->query($sql);
        $tableRows = $statement->fetchAll();
        $tables = array();
        foreach($tableRows as $tableRow){
            $tables[] = $tableRow['TABLE_NAME'];
        }
        
        foreach($tables as $table){
            self::createTableClass($table);
            self::createModelClass($table);
        }
    }
    
    private static function createTableClass($tableName)
    {
        $tableFileName = self::getFilename($tableName, 'tables');
        $className = self::getClassname($tableName);
        $phpFile = fopen($tableFileName, 'w');
        $textString = <<<HERE
<?php
namespace Models\Tables;
use Libraries\TinyPHP\Db\TableBase;
class $className extends TableBase
{
    protected \$_tableName = '$tableName';
}
HERE;
        fwrite($phpFile, $textString);
        fclose($phpFile);
    }
    
    private static function createModelClass($tableName)
    {
        $modelFileName = self::getFilename($tableName);
        $className = self::getClassname($tableName);
        $phpFile = fopen($modelFileName, 'w');
        $textString = <<<HERE
<?php
namespace Models;
class $className
{
HERE;
        $sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . self::$_schemaName . "' AND TABLE_NAME = '" . $tableName  . "'";
//        
//    protected \$_id;
//	
//    public function getId()
//    {
//            return \$this->_id;
//    }
//
//    public function setId($id)
//    {
//            $this->_id = $id;
//            return $this;
//    }
//}
        fwrite($phpFile, $textString);
        fclose($phpFile);
    }
    
    private static function getFilename($tableName, $directory = '')
    {
        $className = self::getClassname($tableName);
        $fileName = Application::$MODELS_DIR;
        if($directory){
            $fileName .= $directory . "/";
        }
        $fileName .= $className . ".php";
        return $fileName;
    }
    
    private static function getClassname($tableName)
    {
        $className = str_replace("_", " ", $tableName);
        $className = ucwords($className);
        $className = str_replace(" ", "", $className);
        if(substr($className, -3) == 'ies'){
            $className = substr($className, 0, -3) . "y";
        }
        if(substr($className, -1) == 's'){
            $className = substr($className, 0, -1);
        }
        return $className;
    }
}