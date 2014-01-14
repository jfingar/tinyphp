<?php
namespace Libraries\TinyPHP\Db;
interface IMappable
{
    public function getTable();
    public function find($pk);
    public function fetchRow(array $params = array());
    public function fetchAll(array $params = array(), $orderBy = '', $limit = '');
    public function save($obj);
    public function delete($obj);
}