<?php

class registroModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    
    public function verificarUsuario($usuario){

        $id = $this->_db->query(
                "SELECT id_usuario, codigo from usuario WHERE usuario = '$usuario'"
                );
        return $id->fetch();
    }
    
    public function verificarEmail($email){
        $id = $this->_db->query(
                "SELECT id_persona from persona WHERE email = '$email'"
                );
        if($id->fetch()){
            return true;
        }
        
        return false;
    }
    
    public function setPersona($nombre, $apellido, $genero, $email, $telefono, $pais, $resumenBiografico){
        $this->_db->prepare(
         "insert into persona(nombre, apellido, genero, email, telefono, pais, resumenBiografico) VALUES(:nombre, :apellido, :genero, :email, :telefono, :pais, :resumenBiografico)"
         )
         ->execute(
                 array(
                     ':nombre' => $nombre,
                     ':apellido' => $apellido,
                     ':genero' => $genero,
                     ':email' => $email,
                     ':telefono' => $telefono,
                     ':pais' => $pais,
                     ':resumenBiografico' => $resumenBiografico
                 )
         );
    }
    
    public function setPersonaRol($id_persona, $id_rol){
        
        $this->_db->query("INSERT INTO persona_rol(id_persana, id_rol) VALUES($id_persona, $id_rol)");

    }
    
    public function getUltimaPersona(){
         $id = $this->_db->query(
                "SELECT MAX(id_persona) as id_persona from persona"
                );
        return $id->fetch();
    }
    
    public function registrarUsuario($nombre, $apellido, $genero, $telefono, $pais, $resumenBiografico, $usuario, $password, $email, $cuenta){
       
        $random = rand(1782598471, 9999999999);

         $this->_db->prepare(
         'insert into persona(nombre, apellido, genero, email, telefono, pais, "resumenBiografico") VALUES(:nombre, :apellido, :genero, :email, :telefono, :pais, :resumenBiografico)'
         )
         ->execute(
                 array(
                     ':nombre' => $nombre,
                     ':apellido' => $apellido,
                     ':genero' => $genero,
                     ':email' => $email,
                     ':telefono' => $telefono,
                     ':pais' => $pais,
                     ':resumenBiografico' => $resumenBiografico
                 )
         );

       
        $persona = $this->_db->query("SELECT MAX(id_persona) AS id_persona FROM persona");
        
        $persona = $persona->fetch();
        
        for($i = 0; $i < count($cuenta); $i++){
            $this->_db->prepare("insert into persona_rol(id_persona, id_rol) VALUES (:id_persona, :id_rol)")
                    ->execute(array(
                       ':id_persona' => $persona['id_persona'],
                       ':id_rol' => $cuenta[$i]
                    ));
        }
        
        if(in_array("3", $cuenta)){
        
            $this->_db->prepare("insert into autor(id_persona) VALUES (:id_persona)")
                    ->execute(array(
                        ':id_persona' => $persona['id_persona']
                    ));
        }
        
        $this->_db->prepare(
                "insert into usuario(usuario, pass, id_persona, fecha, estado, codigo) VALUES (:usuario, :pass, :id_persona, now(), 0, :codigo)"
                )
                ->execute(array(
                   ':usuario' => $usuario,
                   ':pass' => Hash::getHash('md5', $password, HASH_KEY),
                   ':id_persona' => $persona['id_persona'],
                   ':codigo' => $random
                ));
    }
    
    public function getUsuario($id, $codigo){
        $usuario = $this->_db->query(
                "select * from usuario where id_usuario = $id and codigo = $codigo"
                );
        return $usuario->fetch();
    }
    
    public function activarUsuario($id, $codigo){
        $this->_db->query(
                "update usuario set estado = 1 ".
                "where id_usuario = $id and codigo = $codigo"
                );
    }
    
    public function getPaises(){
        $paises = $this->_db->query("select * from pais");
        return $paises->fetchAll();
    }
}

?>