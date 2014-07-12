<?php

namespace Libraries\TinyPHP\Db;

use Libraries\TinyPHP\Db\Adapter;

abstract class TableBase
{

    protected $_tableName;
    protected $_schemaName;
    protected $_primaryKeyField = 'id';
    protected $_dbAdapter;
    protected $_lastStatement;

    public function __construct()
    {
	$this->_dbAdapter = Adapter::GetMysqlAdapter();
    }
    
    public function beginTransaction()
    {
        $this->_dbAdapter->beginTransaction();
    }
    
    public function commit()
    {
        $this->_dbAdapter->commit();
    }
    
    public function rollBack()
    {
        $this->_dbAdapter->rollBack();
    }
    
    public function getLastStatement()
    {
	return $this->_lastStatement;
    }
    
    public function getAffectedRows()
    {
	return $this->_lastStatement->rowCount();
    }

    public function find($pk)
    {
	$params = array(':pk' => $pk);
	$sql = "SELECT * FROM ";
	if($this->_schemaName){
	    $sql .= $this->_schemaName . ".";
	}
	$sql .= "$this->_tableName WHERE $this->_primaryKeyField = :pk";
	$statement = $this->_dbAdapter->prepare($sql);
	$statement->execute($params);
	$row = $statement->fetch();
	$this->_lastStatement = $statement;
	return $row;
    }

    public function fetchRow(array $params = array(), $orderBy = '')
    {
	$sql = "SELECT * FROM ";
	if($this->_schemaName){
	    $sql .= $this->_schemaName . ".";
	}
	$sql .= $this->_tableName;
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
	$sql .= " LIMIT 1";
	$statement = $this->_dbAdapter->prepare($sql);
	$statement->execute($prepared);
	$row = $statement->fetch();
	$this->_lastStatement = $statement;
	return $row;
    }

    public function fetchAll($params = array(), $orderBy = '', $limit = '')
    {
	$sql = "SELECT * FROM ";
	if($this->_schemaName){
	    $sql .= $this->_schemaName . ".";
	}
	$sql .= $this->_tableName;
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
	$sql = "INSERT INTO ";
	if($this->_schemaName){
	    $sql .= $this->_schemaName . ".";
	}
	$sql .= $this->_tableName . " SET ";
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
	$result = $this->rawExec($sql, $prepared);
	if(!$result){
	    return false;
	}
	return $this->_dbAdapter->lastInsertId();
    }

    public function update(array $updateParams, array $whereParams)
    {
	$sql = "UPDATE ";
	if($this->_schemaName){
	    $sql .= $this->_schemaName . ".";
	}
	$sql .= $this->_tableName . " SET ";
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
	$sql = "DELETE FROM ";
	if($this->_schemaName){
	    $sql .= $this->_schemaName . ".";
	}
	$sql .= $this->_tableName . " WHERE ";
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
	return $this->rawExec($sql, $prepared);
    }

    public function rawSelect($sql, $boundParams = array())
    {
	$statement = $this->_dbAdapter->prepare($sql);
	$statement->execute($boundParams);
	$resultSet = $statement->fetchAll();
	$this->_lastStatement = $statement;
	return $resultSet;
    }

    public function rawExec($sql, $boundParams = array())
    {
	$statement = $this->_dbAdapter->prepare($sql);
	$resultSet = $statement->execute($boundParams);
	$this->_lastStatement = $statement;
	return $resultSet;
    }

}
