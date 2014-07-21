<?php

class ajaxModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    
    public function getPaises(){
        $paises = $this->_db->query(
                "select * from pais"
                );
        return $paises->fetchAll();
    }
    
    public function getRevistas(){
        $revista = $this->_db->query(
                "select * from revista"
                );
        return $revista->fetchAll();
    }
    
    public function getMaterias(){
        $materia = $this->_db->query(
                "select * from materia"
                );
        return $materia->fetchAll();
    }
    
    
    public function getIdiomas(){
        $idioma = $this->_db->query(
                "select * from idioma"
                );
        return $idioma->fetchAll();
    }
    
    /*public function getCiudades($pais){
        $ciudades = $this->_db->query(
                "select * from ciudades where pais=$pais"
                );
        $ciudades->setFetchMode(PDO::FETCH_ASSOC);
        return $ciudades->fetchAll();
    }
    
    public function insertarCiudad($ciudad, $pais){
        $this->_db->query(
                "INSERT INTO ciudades(ciudad, pais) VALUES ('$ciudad', $pais);"
                );
    }*/
}

?>