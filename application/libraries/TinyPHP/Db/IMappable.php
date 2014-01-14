<?php
namespace Libraries\TinyPHP\Db;
interface IMappable
{
    public function find($pk);
    public function fetchRow(array $params = array());
    public function fetchAll($params = array(), $orderBy = '', $limit = '');
    public function save($obj);
    public function delete($obj = null, array $params = array());
}