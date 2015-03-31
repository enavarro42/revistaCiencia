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

    public function getUsuarioByFiltro($filtro){

        $sql = "SELECT DISTINCT p.\"primerNombre\", p.apellido, r.rol, a.nombre as \"area\" FROM persona p, persona_rol pr, persona_area pa, rol r, area a WHERE p.id_persona = pr.id_persona and p.id_persona = pa.id_persona and ";

        if(isset($filtro["rol"])){
            $sql .= "pr.id_rol = ". $filtro["rol"] . " and r.id_rol = ". $filtro["rol"] ." and ";
        }else{
            $sql .= "pr.id_rol = r.id_rol and ";
        }

        if(isset($filtro["area"])){
            $sql .= "pa.id_area = ". $filtro["area"] . " and a.id_area = ". $filtro["area"] . " ";
        }else{
            $sql .= "pa.id_area = a.id_area ";
        }

        $sql .= "ORDER BY p.\"primerNombre\", p.apellido asc";

        $datos = $this->_db->query($sql);
        return $datos->fetchAll();
    }

    public function getManuscritosParaAdmin(){
        $manuscrito = $this->_db->query("SELECT m.id_manuscrito, m.titulo, r.nombre, o.fecha FROM manuscrito m, revista r, obra o WHERE m.id_obra = o.id_obra and o.issn = r.issn order by r.issn");
        return $manuscrito->fetchAll();
    }

    
}

?>