<?php

class Model{
    protected $_db;
    
    public function __construct(){
        $this->_db = new Database();
        $this->_db->query('SET NAMES UTF8');
    }
}

?>