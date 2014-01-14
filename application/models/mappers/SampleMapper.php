<?php
namespace Models\Mappers;
use Libraries\TinyPHP\Db\IMappable;
use Libraries\TinyPHP\Db\MapperBase;
use Models\SampleModel;
class SampleMapper extends MapperBase implements IMappable
{
    protected $_tableName = 'table_name';
    
    /**
     * Retrieve a row by Primary Key and return an object with properties mapped.
     * Return false on row not found
     * 
     * @param int $pk
     * @return boolean | \Models\SampleModel
     */
    public function find($pk)
    {
        $row = parent::find($pk);
        if(!$row){
            return false;
        }
        $sampleModel = new SampleModel();
        $sampleModel->setId($row['id'])
                    ->setProperty1($row['property_1'])
                    ->setProperty2($row['property_2'])
                    ->setProperty3($row['property_3']);
        return $sampleModel;
    }
    
    /**
     * Fetch a single row based on sql WHERE clause parameters (filter)
     * Pass an array formatted as key = filter column name | value = filter value
     * 
     * @param array $params
     * @return boolean|\Models\SampleModel
     */
    public function fetchRow(array $params = array())
    {
        $row = parent::fetchRow($params);
        if(!$row){
            return false;
        }
        $sampleModel = new SampleModel();
        $sampleModel->setId($row['id'])
                    ->setProperty1($row['property_1'])
                    ->setProperty2($row['property_2'])
                    ->setProperty3($row['property_3']);
        return $sampleModel;
    }
    
    /**
     * Fetch rows based on sql WHERE clause parameters (filter) and return an array of objects
     * 
     * @param array $params | Array formatted as key = filter column name | value = filter value
     * @param string $orderBy | "ORDER BY" clause (do not include keywords ORDER BY)
     * @param int $limit | "LIMIT" clause (do not include keyword LIMIT)
     * @return array
     */
    public function fetchAll($params = array(), $orderBy = '', $limit = '')
    {
        $rowSet = parent::fetchAll($params, $orderBy, $limit);
        $resultSet = array();
        foreach($rowSet as $row){
            $sampleModel = new SampleModel();
            $sampleModel->setId($row['id'])
                    ->setProperty1($row['property_1'])
                    ->setProperty2($row['property_2'])
                    ->setProperty3($row['property_3']);
            $resultSet[] = $sampleModel;
        }
        return $resultSet;
    }
    
    public function save($obj)
    {
        
    }
    
    public function delete($obj = null, $params = array())
    {
        
    }
}