<?php
/**
 * Mappers are an extra "convenience" layer that use the base Table object methods to interact with table rows and map them to Model objects.
 * If something you are trying to do is not available via the mapper, you can a) write new methods in here or b) use the Table object directly by calling the getTable() method.
 */
namespace Models\Mappers;
use Libraries\TinyPHP\Db\IMappable;
use Models\SampleModel;
use Models\Tables\SampleTable;
class SampleMapper implements IMappable
{
    private $_table;
    
    /**
     * Get an instance of the underlying Table class, which can use methods hidden in TableBase.php directly.
     * 
     * @return SampleTable
     */
    public function getTable()
    {
        if($this->_table === null){
            $this->_table = new SampleTable();
        }
        return $this->_table;
    }
    
    /**
     * Retrieve a row by Primary Key and return an object with properties mapped.
     * Return false on row not found
     * 
     * @param int $pk
     * @return boolean | \Models\SampleModel
     */
    public function find($pk)
    {
        $row = $this->getTable()->find($pk);
        if(!$row){
            return false;
        }
        $sampleModel = new SampleModel();
        $this->_map($sampleModel, $row);
        return $sampleModel;
    }
    
    /**
     * Fetch a single row based on sql WHERE clause parameters
     * 
     * @param array $params | Array formatted as key = column name | value = value
     * @return boolean | SampleModel
     */
    public function fetchRow(array $params = array(), $orderBy = '')
    {
        $row = $this->getTable()->fetchRow($params, $orderBy);
        if(!$row){
            return false;
        }
        $sampleModel = new SampleModel();
        $this->_map($sampleModel, $row);
        return $sampleModel;
    }
    
    /**
     * Fetch rows based on sql WHERE clause parameters and return an array of objects
     * 
     * @param array $params | Array formatted as key = column name | value = value
     * @param string $orderBy | "ORDER BY" clause (do not include keywords ORDER BY)
     * @param int $limit | "LIMIT" clause (do not include keyword LIMIT)
     * @return array
     */
    public function fetchAll(array $params = array(), $orderBy = '', $limit = '')
    {
        $rowSet = $this->getTable()->fetchAll($params,$orderBy,$limit);
        $resultSet = array();
        foreach($rowSet as $row){
            $sampleModel = new SampleModel();
            $this->_map($sampleModel, $row);
            $resultSet[] = $sampleModel;
        }
        return $resultSet;
    }
    
    /**
     * Save or update the object provided. If an id is set on the object, it will perform an update on that row. Otherwise it will create a new row.
     * 
     * @param type SampleModel
     * @return void
     */
    public function save($obj)
    {
        $params = array(
            "property1" => $obj->getProperty1(),
            "property2" => $obj->getProperty2()
        );
        $pk = (int) $obj->getId();
        if($pk > 0){
            $this->getTable()->update($params, array("id" => $pk));
        }else{
            $pk = $this->getTable()->insert($params);
            $obj->setId($pk);
        }
    }
    
    /**
     * Delete the object's corresponding table row. id must be set on the object
     * 
     * @param type SampleModel
     */
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
        $obj->setId($row['id'])
            ->setProperty1($row['property1'])
            ->setProperty2($row['property2']);
    }
}