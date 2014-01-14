<?php
namespace Libraries\TinyPHP\Db;
use Libraries\TinyPHP\Db\Adapter;
abstract class TableBase
{
    protected $_tableName;
    protected $_primaryKeyField = 'id';
    protected $_dbAdapter;
    
    public function __construct()
    {
        $this->_dbAdapter = Adapter::GetMysqlAdapter();
    }
    
    public function find($pk)
    {
        $params = array(':pk' => $pk);
        $sql = "SELECT * FROM $this->_tableName WHERE $this->_primaryKeyField = :pk";
        $statement = $this->_dbAdapter->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();
        return $row;
    }
    
    public function fetchRow(array $params = array())
    {
        $sql = "SELECT * FROM $this->_tableName";
        $prepared = array();
        if(!empty($params)){
            $sql .= " WHERE ";
            $i = 1;
            foreach($params as $columnName => $value){
                $binding = ":" . $columnName;
                $sql .= "$columnName = $binding";
                if($i < count($params)){
                    $sql .= " AND ";
                }
                $prepared[$binding] = $value;
                $i++;
            }
        }
        $statement = $this->_dbAdapter->prepare($sql);
        $statement->execute($prepared);
        $row = $statement->fetch();
        return $row;
    }
    
    public function fetchAll($params = array(), $orderBy = '', $limit = '')
    {
        $sql = "SELECT * FROM $this->_tableName";
        $prepared = array();
        if(!empty($params)){
            $sql .= " WHERE ";
            $i = 1;
            foreach($params as $columnName => $value){
                $binding = ":" . $columnName;
                $sql .= "$columnName = $binding";
                if($i < count($params)){
                    $sql .= " AND ";
                }
                $prepared[$binding] = $value;
                $i++;
            }
        }
        if($orderBy){
            $sql .= " ORDER BY $orderBy";
        }
        if($limit){
            $sql .= " LIMIT $limit";
        }
        return $this->rawSelect($sql, $prepared);
    }
    
    public function insert(array $params)
    {
        $sql = "INSERT INTO " . $this->_tableName . " SET ";
        $prepared = array();
        $i = 1;
        foreach($params as $columnName => $value){
            $binding = ":" . $columnName;
            $sql .= $columnName . " = " . $binding;
            $prepared[$binding] = $value;
            if($i < count($params)){
                $sql .= ", ";
            }
            $i++;
        }
        $result = $this->rawExec($sql,$prepared);
        if(!$result){
            return false;
        }
        return $this->_dbAdapter->lastInsertId();
    }
    
    public function update(array $updateParams, array $whereParams)
    {
        $sql = "UPDATE " . $this->_tableName . " SET ";
        $prepared = array();
        $i = 1;
        foreach($updateParams as $columnName => $value){
            $binding = ":" . $columnName;
            $sql .= $columnName . " = " . $binding;
            $prepared[$binding] = $value;
            if($i < count($updateParams)){
                $sql .= ", ";
            }
            $i++;
        }
        $sql .= " WHERE ";
        $j = 1;
        foreach($whereParams as $columnName => $value){
            $binding = ":" . $columnName;
            $sql .= $columnName . " = " . $binding;
            $prepared[$binding] = $value;
            if($j < count($whereParams)){
                $sql .= " AND ";
            }
            $j++;
        }
        return $this->rawExec($sql, $prepared);
    }
    
    public function delete(array $whereParams)
    {
        $sql = "DELETE FROM " . $this->_tableName . " WHERE ";
        $i = 1;
        $prepared = array();
        foreach($whereParams as $columnName => $value){
            $binding = ":" . $columnName;
            $sql .= $columnName . " = " . $binding;
            $prepared[$binding] = $value;
            if($i < count($whereParams)){
                $sql .= " AND ";
            }
            $i++;
        }
        return $this->rawExec($sql,$prepared);
    }
    
    public function rawSelect($sql,$boundParams = array())
    {
        $statement = $this->_dbAdapter->prepare($sql);
        $statement->execute($boundParams);
        return $statement->fetchAll();
    }
    
    public function rawExec($sql,$boundParams = array())
    {
        $statement = $this->_dbAdapter->prepare($sql);
        return $statement->execute($boundParams);
    }
}