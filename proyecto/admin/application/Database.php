<?php

class Database extends PDO{
    //private $_dnsPostgre = ''
    public function __construct(){

        // mysql
 
        // $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
        
        // $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
        
        // parent::__construct($dsn, DB_USER, DB_PASS, $options);


        
        // postgres
        parent::__construct(
                'pgsql:host=' . DB_HOST . 
                ';dbname=' . DB_NAME . 
                ';port=' . DB_PORT .
                ';user=' . DB_USER .
                ';password=' . DB_PASS
                );
        
        

    }
}

?>