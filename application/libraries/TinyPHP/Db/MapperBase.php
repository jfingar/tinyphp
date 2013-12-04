<?php
namespace Libraries\TinyPHP\Db;
use Libraries\TinyPHP\Db\Adapter;
use Libraries\TinyPHP\Application;
use \Exception;
abstract class MapperBase
{
    private $adapter;
    protected $primary_key = 'id';
    protected $primary_key_getter = 'getId';
    protected $primary_key_setter = 'setId';
    protected $schema_name;
    protected $table_name;
    protected $model_name;
    
    abstract protected function setProperties($obj,$row);
    abstract protected function getProperties($obj);
    
    public function __construct()
    {
        $this->adapter = Adapter::GetMysqlAdapter();
        if(!$this->schema_name){
            $config = Application::$config;
            $this->schema_name = isset($config['db_name']) ? $config['db_name'] : null;
        }
        if(!$this->schema_name){
            throw new Exception("Please define a schema name in your config or in " . get_class($this));
        }
        if(!$this->table_name){
            throw new Exception("Please define a table name in " . get_class($this));
        }
        if(!$this->model_name){
            throw new Exception("Please specify the model name (\$model_name) in " . get_class($this));
        }
    }
    
    public function find($pk)
    {
        $prepared = array(':pk' => $pk);
        $sql = "SELECT * FROM " . $this->schema_name . "." . $this->table_name . " WHERE " . $this->primary_key . " = :pk LIMIT 1";
        $statement = $this->adapter->prepare($sql);
        $statement->execute($prepared);
        $row = $statement->fetch();
        if(empty($row)){
            return false;
        }
        $obj = new $this->model_name();
        $this->setProperties($obj,$row);
        return $obj;
    }
    
    public function fetchRow($where = '',$params = array())
    {
        $sql = "SELECT * FROM " . $this->schema_name . "." . $this->table_name;
        if($where){
            $sql .= " WHERE " . $where;
        }
        $sql .= " LIMIT 1";
        $statement = $this->adapter->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();
        if(empty($row)){
            return false;
        }
        $obj = new $this->model_name();
        $this->setProperties($obj,$row);
        return $obj;
    }
    
    public function fetchAll($where = '',$params = array())
    {
        $sql = "SELECT * FROM " . $this->schema_name . "." . $this->table_name;
        if($where){
            $sql .= " WHERE " . $where;
        }
        $statement = $this->adapter->prepare($sql);
        $statement->execute($params);
        $resultSet = $statement->fetchAll();
        if(empty($resultSet)){
            return array();
        }
        $collection = array();
        foreach($resultSet as $row){
            $obj = new $this->model_name();
            $this->setProperties($obj,$row);
            $collection[] = $obj;
        }
        return $collection;
    }
    
    public function toArray($objectOrCollection)
    {
        $returnArray = array();
        if(is_array($objectOrCollection)){
            foreach($objectOrCollection as $k => $obj){
                $returnArray[$k] = $this->objectToArray($obj);
            }
        }else{
            $returnArray = $this->objectToArray($objectOrCollection);
        }
        return $returnArray;
    }
    
    private function objectToArray($obj)
    {
        $returnArray = array();
        $properties = $this->getProperties($obj);
        foreach($properties as $property => $value){
            $returnArray[$property] = $value;
        }
        return $returnArray;
    }
    
    public function save($obj)
    {
        if($obj->{$this->primary_key_getter}()){
            $sql = "UPDATE ";
        }else{
            $sql = "INSERT INTO ";
        }
        $sql .= $this->schema_name . "." . $this->table_name;
        $sql .= " SET ";
        $params = $this->getProperties($obj);
        $prepared = array();
        $i = 1;
        foreach($params as $param => $value){
            $prepared[':' . $param] = $value;
            $sql .= $param . " = :" . $param;
            if($i < count($params)){
                $sql .= ", ";
            }
            $i++;
        }
        if($obj->{$this->primary_key_getter}()){
            $sql .= " WHERE " . $this->primary_key . " = :id";
        }
        $statement = $this->adapter->prepare($sql);
        $statement->execute($prepared);
        $obj->{$this->primary_key_setter}($this->adapter->lastInsertId());
    }
    
    public function delete($obj = null,$where = '')
    {
        if($obj){
            $sql = "DELETE FROM " . $this->schema_name . "." . $this->table_name . " WHERE " . $this->primary_key . " = " . $obj->{$this->primary_key_getter}();
            $this->adapter->exec($sql);
        }else if($where){
            $sql = "DELETE FROM " . $this->schema_name . "." . $this->table_name . " WHERE " . $where;
            $this->adapter->exec($sql);
        }else{
            throw new Exception("Please provide either an object (arg 1) or a where clause (arg 2) for delete");
        }
    }
}