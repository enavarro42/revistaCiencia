<?php

class aclModel extends Model{
    public function __construct(){
        parent::__construct();
    }

    //--------------------eliminar-----------------
    public function eliminarRol($ids){
    	$this->_db->query("DELETE FROM permiso_rol WHERE id_rol IN($ids)");
    	$this->_db->query("DELETE FROM persona_rol WHERE id_rol IN($ids)");
    	$this->_db->query("DELETE FROM responsable WHERE id_rol IN($ids)");
    	$this->_db->query("DELETE FROM rol WHERE id_rol IN($ids)");
    }

    //--------------------SET---------------------

    public function setRol($rol){
    	$this->_db->query(
    		"INSERT INTO rol(rol) VALUES ('$rol')"
    		);
    }

    public function editarRol($id_rol, $rol){
    	$this->_db->query("UPDATE rol SET rol='$rol' WHERE id_rol = $id_rol");
    }

    public function setPermiso($permiso, $clave){
    	$this->_db->query("INSERT INTO permiso(permiso, clave) VALUES ('$permiso', '$clave')");
    }

    public function editarPermiso($permiso, $clave){
    	$this->_db->query("UPDATE permiso SET permiso='$permiso', clave='$clave' WHERE id_permiso = $id_permiso");
    }

    public function setPermisoRol($id_permiso, $id_rol, $estado){
    	$this->_db->query("INSERT INTO permiso_rol(id_permiso, id_rol, estado) VALUES ($id_permiso, $id_rol, $estado)");
    }

    public function eliminarPermisoRol($id_rol){
        $this->_db->query("DELETE FROM permiso_rol WHERE id_rol = $id_rol");
    }

    public function editarPermisoRol($id_permiso, $id_rol, $estado){
        aclModel::setPermisoRol($id_permiso, $id_rol, $estado);
    }


    //--------------------GET---------------------

    public function getPaises(){
        $paises = $this->_db->query("select * from pais");
        return $paises->fetchAll();
    }

    public function getRoles(){

        $roles = $this->_db->query("select * from rol order by rol");
    	return $roles->fetchAll();
    }

    public function getRol($id_rol){
        
        $id_rol = (int) $id_rol;
        
        $datos = $this->_db->query(
                "SELECT rol from rol " .
                "WHERE id_rol = $id_rol "
                );
        if($datos){
            $datos = $datos->fetch();
            return $datos['rol'];
        }

        return false;

    }


    public function getRolId($rol){
    	$datos = $this->_db->query("SELECT id_rol from WHERE rol = '$rol'");

        if($datos){
            $datos = $datos->fetch();
            return $datos['id_rol'];
        }

        return false;
    }

    public function getPermisosRol($id_rol){
        $permisos = $this->_db->query("select * from permiso_rol where id_rol = $id_rol");
        return $permisos->fetchAll();
    }

    public function getPermisos(){
    	$permisos = $this->_db->query("select * from permiso order by seccion");
    	return $permisos->fetchAll();
    }

	public function getPermisoClave($id_permiso){

		$clave = $this->_db->query(
			"select clave from permiso ".
			"where id_permiso = " . (int) $id_permiso
			);

		$clave = $clave->fetch();
		return $clave['clave'];

	}

	public function getPermisoNombre($id_permiso){

		$nombre = $this->_db->query(
			"select permiso from permiso ".
			"where id_permiso = " . (int) $id_permiso
			);

		$nombre = $nombre->fetch();
		return $nombre['permiso'];

	}

    public function getLastRol(){
        $rol = $this->_db->query(
            "select * from rol order by id_rol DESC LIMIT 1"
            );
        $rol = $rol->fetch();
        return $rol['id_rol'];
    }
}

?>