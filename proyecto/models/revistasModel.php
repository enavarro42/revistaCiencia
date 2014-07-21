<?php

class revistasModel extends Model{
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getRevistas(){
        $revistas = $this->_db->query("select nombre from revista");
        return $revistas->fetchAll();
    }
    
    public function getRevista($revista){
        $revista = $this->_db->query("select * from revista where nombre = '$revista'");
        return $revista->fetch();
    }
}

?>