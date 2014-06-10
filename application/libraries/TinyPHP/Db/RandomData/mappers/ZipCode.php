<?php
namespace Libraries\TinyPHP\Db\RandomData\Mappers;
use Libraries\TinyPHP\Db\IMappable;
use Libraries\TinyPHP\Db\RandomData\ZipCode AS ZipCode_Model;
use Libraries\TinyPHP\Db\RandomData\Tables\ZipCode AS ZipCode_Table;
class ZipCode implements IMappable
{
    private $_table;
        
    public function getTable()
    {
        if($this->_table === null){
            $this->_table = new ZipCode_Table();
        }
        return $this->_table;
    }
            
    public function find($pk)
    {
        $row = $this->getTable()->find($pk);
        if(!$row){
            return false;
        }
        $obj = new ZipCode_Model();
        $this->_map($obj, $row);
        return $obj;
    }
        
    public function fetchRow(array $params = array(), $orderBy = '')
    {
        $row = $this->getTable()->fetchRow($params, $orderBy);
        if(!$row){
            return false;
        }
        $obj = new ZipCode_Model();
        $this->_map($obj, $row);
        return $obj;
    }
        
    public function fetchAll(array $params = array(), $orderBy = '', $limit = '')
    {
        $rowSet = $this->getTable()->fetchAll($params, $orderBy, $limit);
        $resultSet = array();
        foreach($rowSet as $row){
            $obj = new ZipCode_Model();
            $this->_map($obj, $row);
            $resultSet[] = $obj;
        }
        return $resultSet;
    }
            
    public function save($obj)
    {
        $params = array(
	    "id" => $obj->getId(),
	    "city" => $obj->getCity(),
	    "state" => $obj->getState(),
	    "zip" => $obj->getZip(),
	    "latitude" => $obj->getLatitude(),
	    "longitude" => $obj->getLongitude(),
	    "county" => $obj->getCounty()
	);

	$pk = (int) $obj->getId();
        if($pk > 0){
            $this->getTable()->update($params, array("id" => $pk));
        }else{
            $pk = $this->getTable()->insert($params);
            $obj->setId($pk);
        }
    }
		
    public function delete($obj)
    {
        $id = $obj->getId();
        if(!$id){
            return false;
        }
        $this->getTable()->delete(array("id" => $id));
    }
		
    public function _map($obj, $row)
    {
	$obj->setId($row['id']);
	$obj->setCity($row['city']);
	$obj->setState($row['state']);
	$obj->setZip($row['zip']);
	$obj->setLatitude($row['latitude']);
	$obj->setLongitude($row['longitude']);
	$obj->setCounty($row['county']);
    }
}