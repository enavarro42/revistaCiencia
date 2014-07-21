<?php

class oficinaModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    
    public function getDatosOficina(){
        $descripcion = $this->_db->query("select * from oficina where tipo = 'Publicaciones'");
        return $descripcion->fetch();
    }
}

?>