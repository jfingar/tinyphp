<?php
namespace Libraries\TinyPHP\Db;
use Libraries\TinyPHP\Db\Adapter;
abstract class MapperBase
{
    protected $_tableName;
    protected $_primaryKeyField = 'id';
    protected $_dbAdapter;
    
    public function __construct()
    {
        $this->_dbAdapter = Adapter::GetMysqlAdapter();
    }
    
    protected function find($pk)
    {
        $params = array(':pk' => $pk);
        $sql = "SELECT * FROM $this->_tableName WHERE $this->_primaryKeyField = :pk";
        $this->_dbAdapter->prepare($sql);
        $statement = $this->_dbAdapter->execute($params);
        $row = $statement->fetch();
        return $row;
    }
    
    protected function fetchRow(array $params = array())
    {
        $sql = "SELECT * FROM $this->_tableName";
        if(!empty($params)){
            $sql .= " WHERE ";
            $prepared = array();
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
        $this->_dbAdapter->prepare($sql);
        $statement = $this->_dbAdapter->execute($prepared);
        $row = $statement->fetch();
        return $row;
    }
    
    protected function fetchAll($params = array(), $orderBy = '', $limit = '')
    {
        $sql = "SELECT * FROM $this->_tableName";
        if(!empty($params)){
            $sql .= " WHERE ";
            $prepared = array();
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
        $this->_dbAdapter->prepare($sql);
        $statement = $this->_dbAdapter->execute($prepared);
        $rowSet = $statement->fetchAll();
        return $rowSet;
    }
}