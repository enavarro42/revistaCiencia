<?php

class rolModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    
    public function getIdRol($rol){

        $id = $this->_db->query(
                "SELECT id_rol from rol WHERE rol = '$rol'"
                );
        return $id->fetch();
    }
}

?>