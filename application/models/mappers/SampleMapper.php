<?php
namespace Models\Mappers;
use Libraries\TinyPHP\Db\MapperBase;
class SampleMapper extends MapperBase
{
    protected $table_name = 'table_name';
    protected $model_name = '\Models\SampleModel';
    
    protected function setProperties($obj,$row)
    {
        $obj->setId($row['id'])
            ->setProperty1($row['column_1'])
            ->setProperty2($row['column_2'])
            ->setProperty3($row['column_3']);
    }
    
   protected function getProperties($obj)
   {
       return array(
           'id' => $obj->getId(),
           'column_1' => $obj->getProperty1(),
           'column_2' => $obj->getProperty2(),
           'column_3' => $obj->getProperty3()
       );
   }
}