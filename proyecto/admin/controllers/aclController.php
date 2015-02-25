<?php

class aclController extends Controller{
    
    private $acl;
    private $error = array();
    
    public function __construct(){
        parent::__construct();
        $this->acl = $this->loadModel('acl');
    }

    public function index(){
        $view_key = "ver_grupo_usuario";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();


        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }


        //verificar si tiene permisos para eliminar

        $eliminar = 1;

        $view_key = "eliminar_grupo_usuario";

        if(array_key_exists($view_key, $this->_view->acl) == false){
            $eliminar = 0;
        }

        $this->_view->eliminar = $eliminar;

    	$roles = $this->acl->getRoles();

    	$this->_view->setJs(array("js_acl"));

    	$this->_view->roles = $roles;

    	$this->_view->titulo = 'Grupo de Usuarios';
    	$this->_view->renderizar('index');
    }

    public function crear(){


        $view_key = "crear_grupo_usuario";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();


        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }
    	
    	$this->_view->permisos = $this->acl->getPermisos();

        $this->_view->data['value_rol'] = "";

        if($this->getInt("enviar") == 1){

            $this->_view->data['value_rol'] = $_POST['rol'];

            $permisos_seleccionados = $_POST['check_permiso'];
            $this->_view->permisos_seleccionados = $permisos_seleccionados;

            if(aclController::validarRol()){
                $rol = $this->getSql('rol');
                $this->acl->setRol($rol);

                $id_rol = $this->acl->getLastRol();

                for($i = 0; $i < count($_POST['check_permiso']); $i++){
                    $this->acl->setPermisoRol($permisos_seleccionados[$i], $id_rol, 1);
                }

                $this->redireccionar('acl');
            }
        }

        $this->_view->errores = $this->error;

        $this->_view->titulo = 'Crear Grupo de Usuario';
        $this->_view->renderizar('form');

    }

    public function editar($id_rol = false){

        $view_key = "editar_grupo_usuario";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();


        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $rol = $this->acl->getRol($this->filtrarInt($id_rol));

        $permisos_seleccionados = array();

        //si se paso algo por parametro y se comprueba si existe
        if($this->filtrarInt($id_rol) && $rol){

            // se obtienen todos los permisos
            $this->_view->permisos = $this->acl->getPermisos();

            //se obtiene los permisos de ese rol
            $permisos = $this->acl->getPermisosRol($this->filtrarInt($id_rol));

            //mostrar el rol
            $this->_view->data['value_rol'] = trim($rol);

            //seleccionar todos los permisos de ese rol
            for($i=0; $i<count($permisos); $i++){
                $permisos_seleccionados[] = $permisos[$i]['id_permiso'];
            }

            //si se preciono guardar
            if($this->getInt("enviar") == 1){
                //tomar el valor del campo rol
                $this->_view->data['value_rol'] = $_POST['rol'];

                //obtener los permisos seleccionados
                $permisos_selected = $_POST['check_permiso'];

                var_dump($permisos_selected);

                //si es valido
                if(aclController::validarRol()){
                    //entonces edita el rol
                    $this->acl->editarRol($this->filtrarInt($id_rol), $this->getSql('rol'));

                    $this->acl->eliminarPermisoRol($this->filtrarInt($id_rol));

                    //editar los permisos de ese rol
                    for($i = 0; $i < count($_POST['check_permiso']); $i++){
                        $this->acl->editarPermisoRol($permisos_selected[$i], $this->filtrarInt($id_rol), 1);
                    }

                    //redireccionar
                    $this->redireccionar('acl');
                }
                //si no es valido no hacer nada

            }

            $this->_view->permisos_seleccionados = $permisos_seleccionados;


        }else{

            $this->_view->data['value_rol'] = "";
            $this->redireccionar('acl');
        }


        $this->_view->errores = $this->error;

        $this->_view->titulo = 'Editar Grupo de Usuario';
        $this->_view->renderizar('form');

    }

    private function validarRol(){

        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

        $validado = true;
        $this->error['error_rol'] = "";

        if($this->getPostParam('rol') == ''){
            $this->error['error_rol'] = 'Debe llenar el campo Rol.';
            $validado = false;
        }
        else if(!preg_match($exp, $this->getSql('rol'))){
            $this->error['error_rol'] = 'Rol inv&aacute;lido';
            $validado = false;
        }else if($this->acl->getRolId($this->getSql('rol')) != false){
            $this->error['error_rol'] = '&Eacute;ste nombre de rol ya est&aacute; en uso.';
            $validado = false;
        }

        return $validado;
    }


    public function eliminar(){
    	$this->acl->eliminarRol($_POST["ids"]);
    }
}

?>