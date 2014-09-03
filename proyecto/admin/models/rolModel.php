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

    public function getPersonaRol($id_persona = false, $id_rol = false){
    	$persona_rol = false;
    	if($id_persona && $id_rol){
    		$persona_rol = $this->_db->query("SELECT * from persona_rol where id_persona = $id_persona and id_rol = $id_rol");
    	}

    	if($persona_rol)
    		return $persona_rol->fetch();
    	return false;
    }
}

?>