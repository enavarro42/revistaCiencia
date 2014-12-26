<?php

class perfilModel extends Model{
    public function __construct(){
        parent::__construct();
    }

    public function getPersona($id_persona){
        $persona = $this->_db->query(
                "select * from persona where id_persona=$id_persona"
                );
        if($persona === false) return false;

        $persona = $persona->fetch();

        return $persona;
    }

    public function getUsuario($id_persona){
        $usuario = $this->_db->query(
                "select * from usuario where id_persona=$id_persona"
                );
        if($usuario === false) return false;

        $usuario = $usuario->fetch();

        return $usuario;
    }

    public function getPaises(){
        $paises = $this->_db->query("select * from pais");
        return $paises->fetchAll();
    }

    public function editarPerfil($id_persona, $datos){
        $this->_db->query("UPDATE persona SET \"primerNombre\"='".$datos['primerNombre']."', apellido='".$datos['apellido']."', genero='".$datos['genero']."', ".
            "email='".$datos['email']."', telefono='".$datos['telefono']."', pais=".$datos['pais'].", \"resumenBiografico\"='".$datos['resumenBiografico']."', din='".$datos['din']."', filiacion='".$datos['filiacion']."', \"segundoNombre\"='".$datos['segundoNombre']."' ".
            "WHERE id_persona = $id_persona;");



        if(isset($datos['pass'])){

            if($datos['pass'] != ''){
                $this->_db->query("UPDATE usuario SET usuario='".$datos['usuario']."', pass='".Hash::getHash('md5', $datos['pass'], HASH_KEY)."' WHERE id_persona = $id_persona;");
            }else{
                $this->_db->query("UPDATE usuario SET usuario='".$datos['usuario']."' WHERE id_persona = $id_persona;");
            }
        }
    }

}

?>