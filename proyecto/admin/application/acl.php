<?php

class Acl {
	private $_db;
	private $_id_persona;
	private $_id_rol;
	private $_permisos;

	public function __construct($id = false){

		if($id){
			$this->_id_persona = (int) $id;
		}else{
			if(Session::get('id_persona')){
				$this->_id_persona = Session::get('id_persona');
			}else{
				$this->_id_persona = 0;
			}
		}

		$this->_db = new Database();
		$this->_id_rol = $this->getRol();
		$this->_permisos = $this->getPermisoRol();
	}

	public function getRol(){

		$rol = $this->_db->query(
			"select * from rol where rol = '".trim(Session::get('level'))."'"
			);

		$rol = $rol->fetch();
		return $rol['id_rol'];

	}

	public function getPermisoRolId(){
		$ids = $this->_db->query(
			"select id_permiso from permiso_rol ".
			"where id_rol = " . $this->_id_rol
			);

		$ids = $ids->fetchAll();

		$id = array();
		for($i=0; $i<count($ids); $i++){
			$id[] = $ids[$i]['id_permiso'];
		}
		return $id;
	}

	public function getPermisoRol(){
		$permisos = $this->_db->query(
			"select * from permiso_rol ".
			"where id_rol = " . $this->_id_rol
			);

		$permisos = $permisos->fetchAll();

		$data = array();

		for($i=0; $i<count($permisos); $i++){
			$clave = $this->getPermisoClave($permisos[$i]['id_permiso']);

			if($permisos[$i]['estado'] == 1){
				$estado = true;
			}else{
				$estado = false;
			}

			$data[$clave] = array(
				"clave" => $clave,
				"permiso" => $this->getPermisoNombre($permisos[$i]['id_permiso']),
				"estado" => $estado,
				"heredado" => true,
				"id_permiso" => $permisos[$i]['id_permiso']
				);
		}

		return $data;
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

	public function getPermisos(){
		if(isset($this->_permisos) && count($this->_permisos))
			return $this->_permisos;
	}
}

?>