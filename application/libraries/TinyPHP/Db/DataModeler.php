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
            self::createMapperClass($table);
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
	$columnRows = self::getTableColumns($tableName);
        $textString = <<<HERE
<?php
namespace Models;
class $className
{

HERE;
	
	foreach($columnRows as $column){
            $property = self::getProperty($column['COLUMN_NAME']);
            $textString .= <<<HERE
    private $property;\r\n
HERE;
	}
		
	$textString .= "\r\n";
		
	foreach($columnRows as $column){
            $methodName = str_replace("_", " ",$column['COLUMN_NAME']);
            $methodName = ucwords($methodName);
            $getterMethodName = 'get' . str_replace(" ", "", $methodName);
            $setterMethodName = 'set' . str_replace(" ", "", $methodName);
            $property = substr(self::getProperty($column['COLUMN_NAME']),1);
            $textString .= <<<HERE
    public function $getterMethodName()
    {
	return \$this->$property;
    }

    public function $setterMethodName(\$$property)
    {
	\$this->$property = \$$property;
	return \$this;
    }


HERE;
	}
		
	$textString .= '}';
		
        fwrite($phpFile, $textString);
        fclose($phpFile);
    }
    
    private static function createMapperClass($tableName)
    {
        $modelFileName = self::getFilename($tableName, 'mappers');
        $className = self::getClassname($tableName);
        $phpFile = fopen($modelFileName, 'w');
	$columnNames = self::getTableColumns($tableName);
        $textString = <<<HERE
<?php
namespace Models\Mappers;
use Libraries\TinyPHP\Db\IMappable;
use Models\\$className AS {$className}_Model;
use Models\Tables\\$className AS {$className}_Table;
class $className implements IMappable
{
    private \$_table;
        
    public function getTable()
    {
        if(\$this->_table === null){
            \$this->_table = new {$className}_Table;
        }
        return \$this->_table;
    }
            
    public function find(\$pk)
    {
        \$row = \$this->getTable()->find(\$pk);
        if(!\$row){
            return false;
        }
        \$obj = new {$className}_Model();
        \$this->_map(\$obj, \$row);
        return \$obj;
    }
        
    public function fetchRow(array \$params = array())
    {
        \$row = \$this->getTable()->fetchRow(\$params);
        if(!\$row){
            return false;
        }
        \$obj = new {$className}_Model();
        \$this->_map(\$obj, \$row);
        return \$obj;
    }
        
    public function fetchAll(array \$params = array(), \$orderBy = '', \$limit = '')
    {
        \$rowSet = \$this->getTable()->fetchAll(\$params, \$orderBy, \$limit);
        \$resultSet = array();
        foreach(\$rowSet as \$row){
            \$obj = new {$className}_Model();
            \$this->_map(\$obj, \$row);
            \$resultSet[] = \$obj;
        }
        return \$resultSet;
    }
            
    public function save(\$obj)
    {
        \$params = array(

HERE;
	foreach($columnNames as $column){
	    $columnName = $column['COLUMN_NAME'];
	    $methodName = str_replace("_", " ",$columnName);
            $methodName = ucwords($methodName);
            $getterMethodName = 'get' . str_replace(" ", "", $methodName);
	    $textString .= <<<HERE
	    "$columnName" => \$obj->{$getterMethodName}(),

HERE;
	}
	$textString = substr($textString, 0, -3) . "\r\n\t);\r\n\r\n";
        $textString .= <<<HERE
	\$pk = (int) \$obj->getId();
        if(\$pk > 0){
            \$this->getTable()->update(\$params, array("id" => \$pk));
        }else{
            \$pk = \$this->getTable()->insert(\$params);
            \$obj->setId(\$pk);
        }
    }
		
    public function delete(\$obj)
    {
        \$id = \$obj->getId();
        if(!\$id){
            return false;
        }
        \$this->getTable()->delete(array("id" => \$id));
    }
		
    public function _map(\$obj, \$row)
    {

HERE;
	
	foreach($columnNames as $column){
	    $columnName = $column['COLUMN_NAME'];
	    $methodName = str_replace("_", " ",$columnName);
            $methodName = ucwords($methodName);
            $setterMethodName = 'set' . str_replace(" ", "", $methodName);
	    $textString .= <<<HERE
	\$obj->{$setterMethodName}(\$row['$columnName']);

HERE;
	}
	
	$textString .= <<<HERE
    }
}
HERE;
		
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
	
    private static function getProperty($columnName)
    {
	    $property = str_replace("_", " ", $columnName);
	    $property = ucwords($property);
	    $property = str_replace(" ","",$property);
	    $property = "\$_" . lcfirst($property);
	    return $property;
    }
    
    private static function getTableColumns($tableName)
    {
	$sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . self::$_schemaName . "' AND TABLE_NAME = '" . $tableName  . "'";
	$dbAdapter = Adapter::GetMysqlAdapter();
	$statement = $dbAdapter->query($sql);
	$columnRows = $statement->fetchAll();
	return $columnRows;
    }
	
}