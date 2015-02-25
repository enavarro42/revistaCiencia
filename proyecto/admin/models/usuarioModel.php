<?php

class usuarioModel extends Model{
	
    public function __construct(){
        parent::__construct();
    }

    public function eliminar($ids = false){

        if($ids){
            $this->_db->query("DELETE FROM persona_area WHERE id_persona IN($ids)");
            $this->_db->query("DELETE FROM persona_rol WHERE id_persona IN($ids)");
            $this->_db->query("DELETE FROM usuario WHERE id_persona IN($ids)");
            $this->_db->query("DELETE FROM responsable WHERE id_persona IN($ids)");
            $this->_db->query("DELETE FROM persona WHERE id_persona IN($ids)");
        }

    }


    public function setUsuario($datos){

        // var_dump($datos);

        $random = rand(1782598471, 9999999999);

         $this->_db->prepare(
         'insert into persona("primerNombre", apellido, genero, email, telefono, pais, "resumenBiografico", din, filiacion, "segundoNombre") VALUES(:primerNombre, :apellido, :genero, :email, :telefono, :pais, :resumenBiografico, :din, :filiacion, :segundoNombre)'
         )
         ->execute(
                 array(
                     ':primerNombre' => $datos['primerNombre'],
                     ':apellido' => $datos['apellido'],
                     ':genero' => $datos['genero'],
                     ':email' => $datos['email'],
                     ':telefono' => $datos['telefono'],
                     ':pais' => $datos['pais'],
                     ':resumenBiografico' => $datos['resumenBiografico'],
                     ':din' => $datos['din'],
                     ':filiacion' => $datos['filiacion'],
                     ':segundoNombre' => $datos['segundoNombre']

                 )
         );

       
        $persona = $this->_db->query("SELECT MAX(id_persona) AS id_persona FROM persona");
        
        $persona = $persona->fetch();
        
        for($i = 0; $i < count($datos['check_rol']); $i++){
            $this->_db->prepare("insert into persona_rol(id_persona, id_rol) VALUES (:id_persona, :id_rol)")
                    ->execute(array(
                       ':id_persona' => $persona['id_persona'],
                       ':id_rol' => $datos['check_rol'][$i]
                    ));
        }

        for($i = 0; $i < count($datos['check_areas']); $i++){
            $this->_db->prepare("insert into persona_area(id_persona, id_area) VALUES (:id_persona, :id_area)")
                    ->execute(array(
                       ':id_persona' => $persona['id_persona'],
                       ':id_area' => $datos['check_areas'][$i]
                    ));
        }
        
        $this->_db->prepare(
                "insert into usuario(usuario, pass, id_persona, fecha, estado, codigo) VALUES (:usuario, :pass, :id_persona, now(), 0, :codigo)"
                )
                ->execute(array(
                   ':usuario' => $datos['usuario'],
                   ':pass' => Hash::getHash('md5', $datos['pass'], HASH_KEY),
                   ':id_persona' => $persona['id_persona'],
                   ':codigo' => $random
                ));
    }

    public function editarUsuario($id_persona, $datos){

        $this->_db->query("UPDATE persona SET \"primerNombre\"='".$datos['primerNombre']."', apellido='".$datos['apellido']."', genero='".$datos['genero']."', ".
            "email='".$datos['email']."', telefono='".$datos['telefono']."', pais=".$datos['pais'].", \"resumenBiografico\"='".$datos['resumenBiografico']."', din='".$datos['din']."', filiacion='".$datos['filiacion']."', \"segundoNombre\"='".$datos['segundoNombre']."' ".
            "WHERE id_persona = $id_persona;");

         $this->_db->query("DELETE FROM persona_rol WHERE id_persona = $id_persona;");

        for($i = 0; $i < count($datos['check_rol']); $i++){
            $this->_db->prepare("insert into persona_rol(id_persona, id_rol) VALUES (:id_persona, :id_rol)")
                    ->execute(array(
                       ':id_persona' => $id_persona,
                       ':id_rol' => $datos['check_rol'][$i]
                    ));
        }

        $this->_db->query("DELETE FROM persona_area WHERE id_persona = $id_persona;");

        for($i = 0; $i < count($datos['check_areas']); $i++){
            $this->_db->prepare("insert into persona_area (id_persona, id_area) VALUES (:id_persona, :id_area)")
                    ->execute(array(
                       ':id_persona' => $id_persona,
                       ':id_area' => $datos['check_areas'][$i]
                    ));
        }

        if(isset($datos['pass'])){

            if($datos['pass'] != ''){
                $this->_db->query("UPDATE usuario SET usuario='".$datos['usuario']."', pass='".Hash::getHash('md5', $datos['pass'], HASH_KEY)."' WHERE id_persona = $id_persona;");
            }else{
                $this->_db->query("UPDATE usuario SET usuario='".$datos['usuario']."' WHERE id_persona = $id_persona;");
            }
        }

        

    }



    public function editarPerfil($id_persona, $datos){
        $this->_db->query("UPDATE persona SET \"primerNombre\"='".$datos['primerNombre']."', apellido='".$datos['apellido']."', genero='".$datos['genero']."', ".
            "email='".$datos['email']."', telefono='".$datos['telefono']."', pais=".$datos['pais'].", \"resumenBiografico\"='".$datos['resumenBiografico']."', din='".$datos['din']."', filiacion='".$datos['filiacion']."', \"segundoNombre\"='".$datos['segundoNombre']."' ".
            "WHERE id_persona = $id_persona;");


        $this->_db->query("DELETE FROM persona_area WHERE id_persona = $id_persona;");

        for($i = 0; $i < count($datos['check_areas']); $i++){
            $this->_db->prepare("insert into persona_area (id_persona, id_area) VALUES (:id_persona, :id_area)")
                    ->execute(array(
                       ':id_persona' => $id_persona,
                       ':id_area' => $datos['check_areas'][$i]
                    ));
        }

        if(isset($datos['pass'])){

            if($datos['pass'] != ''){
                $this->_db->query("UPDATE usuario SET usuario='".$datos['usuario']."', pass='".Hash::getHash('md5', $datos['pass'], HASH_KEY)."' WHERE id_persona = $id_persona;");
            }else{
                $this->_db->query("UPDATE usuario SET usuario='".$datos['usuario']."' WHERE id_persona = $id_persona;");
            }
        }
    }

    public function setClave($id_persona, $clave){
        $this->_db->query("UPDATE usuario SET pass = '".Hash::getHash('md5', $clave, HASH_KEY)."' WHERE id_persona = $id_persona");
    }

    public function getPersona($id_persona){
        $persona = $this->_db->query(
                "select * from persona where id_persona=$id_persona"
                );
        if($persona === false) return false;

        $persona = $persona->fetch();

        return $persona;
    }

    public function getAllAreas(){
        $area = $this->_db->query("select * from area");
        return $area->fetchAll();
    }

    public function getAreaByPersona($id_persona){
        $area = $this->_db->query("select * from persona_area where id_persona = $id_persona");
        return $area->fetchAll();
    }

    public function getPersonaRol($id_persona){

        $persona = $this->_db->query(
                "select * from persona_rol where id_persona=$id_persona"
                );
        if($persona === false) return false;

        $persona = $persona->fetchAll();

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

    public function getUsuarios(){
        $usuario = $this->_db->query(
                "select * from usuario"
                );
        if($usuario === false) return false;

        $usuario = $usuario->fetch();

        return $usuario;
    }



    public function getUsuariosByFiltro($filtro = false){
    	// $sql = "SELECT p.id_persona, (p.\"primerNombre\" || ' ' || p.apellido) AS nombreCompleto, p.email FROM persona p, persona_rol pr persona_area pa ";
        $sql = "SELECT DISTINCT p.id_persona, (p.\"primerNombre\" || ' ' || p.apellido) AS nombreCompleto, p.email FROM persona p, persona_rol pr, persona_area pa ";
        $bandera = 0;
    	if($filtro){
            $sql .= "WHERE ";
            if(isset($filtro['tipoBusqueda']) && $filtro['tipoBusqueda'] == 'nombre'){
                $nombre = $filtro['busqueda'];
                $sql .= "p.\"primerNombre\" ILIKE '%$nombre%'";
                $bandera = 1;
            }

            if(isset($filtro['tipoBusqueda']) && $filtro['tipoBusqueda'] == 'apellido'){

                if($bandera > 0 ) $sql .= " and ";
                $apellido = $filtro['busqueda'];
                $sql .= "p.apellido ILIKE '%$apellido%'";
            }

            if(isset($filtro['tipoUsuario']) && $filtro['tipoUsuario']){
                if($bandera > 0 ) $sql .= " and ";
                $id_rol = $filtro['tipoUsuario'];
                $sql .= "p.id_persona = pr.id_persona and pr.id_rol = $id_rol";
            }

            if(isset($filtro['area']) && $filtro['area']){
                if($bandera > 0 ) $sql .= " and ";
                $id_area = $filtro['area'];
                $sql .= "p.id_persona = pa.id_persona and pa.id_area = $id_area ";
            }
    		
    	}else{
            //$sql .= "where p.id_persona = pr.id_persona and pr.id_rol = 1 ";
            $sql .= "where p.id_persona = pr.id_persona and p.id_persona = pa.id_persona ";
        }

        if(trim($_SESSION["level"]) != "Admin"){
            $sql .= "and p.id_persona <> ".$_SESSION["id_persona"]." ORDER BY \"nombrecompleto\" ";
        }else{
            $sql .= "ORDER BY \"nombrecompleto\" ";
        }

        // var_dump($sql);

        $result = $this->_db->query($sql);

        if($result === false){
            return false;
        }

        return $result->fetchAll();

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

    public function getEmail($email){
        $email = $this->_db->query(
                "SELECT * from persona WHERE email = '$email'"
                );

        if($email)
            return $email->fetch();

        return false;
    }

    public function verificarDin($din){
        $id = $this->_db->query(
                "SELECT din from persona WHERE din = '$din'"
                );
        if($id->fetch()){
            return true;
        }
        
        return false;
    }

}

?>