<?php

class reporteModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    
    public function getUsuarioPorArea(){
        $datos = $this->_db->query(
                "select a.nombre, count(pa.id_area) as cantidad from persona_area pa, area a where a.id_area = pa.id_area group by pa.id_area, a.nombre order by pa.id_area asc");
       return $datos->fetchAll();
    }

    public function getRolPorPersona(){
        $datos = $this->_db->query(
                "select r.rol, count(pr.id_rol) as cantidad from persona_rol pr, rol r where r.id_rol = pr.id_rol group by pr.id_rol, r.rol order by pr.id_rol asc");
       return $datos->fetchAll();
    }


    public function getManuscritoPorEstatus(){
        $datos = $this->_db->query(
                "select e.estatus, count(m.id_manuscrito) as cantidad from estatus e, manuscrito m where m.id_estatus = e.id_estatus group by e.estatus order by e.estatus asc");
       return $datos->fetchAll();
    }



    
}

?>