<?php

class autorModel extends Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function setAutor($id_persona){
        $this->_db->query(
                "INSERT INTO autor(id_persona) values($id_persona)"
                );
    }
    
    public function setAutorObra($id_autor, $id_obra){
       $this->_db->query(
                "INSERT INTO autor_obra(id_autor, id_obra) values($id_autor, $id_obra)"
                );
    }
    
    public function getIdAutor($id_persona){
        $autor = $this->_db->query("select id_autor from autor where id_persona = $id_persona");
        return $autor->fetch();
    }

    public function getDatos($id_persona){
        $persona = $this->_db->query(
                "select * from persona where id_persona=$id_persona"
                );
        
        $arreglo = $persona->fetch();

        return $arreglo;
    }
    
    
    public function getCountAutor($id_persona){
       $id = $this->_db->query(
                "SELECT * from autor where id_persona = $id_persona"
                );
        return $id->rowCount();
    }
}

?>