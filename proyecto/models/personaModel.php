<?php

class personaModel extends Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    // public function setPersonaObra($id_persona, $id_obra){
    //    $this->_db->query(
    //             "INSERT INTO persona_obra(id_persona, id_obra) values($id_persona, $id_obra)"
    //             );
    // }

    // public function setCorrespondencia($id_persona, $id_manuscrito, $permiso = 0){
    //     $this->_db->query(
    //              "INSERT INTO correspondencia(id_persona, id_manuscrito, permiso) values($id_persona, $id_manuscrito, $permiso)"
    //              );
    // }

    public function setPermisoResponsable($id_responsable, $permiso){
        $this->_db->query(
                 "UPDATE responsable SET permiso=$permiso WHERE id_responsable = $id_responsable"
                 );
    }

    public function getPersonaResponsable($id_persona, $id_manuscrito){
         $persona = $this->_db->query(
                "select * from responsable where id_persona = $id_persona and id_manuscrito = $id_manuscrito"
                );
        return $persona->fetch();
    }

    public function getPersonaByEmail($email){
         $persona = $this->_db->query("select * from persona where email = '$email'");
        return $persona->fetch();
    }

    public function getAutorCorrespondencia($id_manuscrito){
        $persona = $this->_db->query(
                "select * from responsable where id_manuscrito = $id_manuscrito and correspondencia = 1"
                );
        return $persona->fetch();
    }

    public function getDatos($id_persona){
        $persona = $this->_db->query(
                "select * from persona where id_persona=$id_persona"
                );
        
        $arreglo = $persona->fetch();

        return $arreglo;
    }

    public function getEmailByResponsableId($id_responsable){
        $email = $this->_db->query("select p.id_persona, p.email from persona p, responsable r where r.id_responsable = $id_responsable and r.id_persona = p.id_persona");
        return $email->fetchAll();
    }
    
}

?>