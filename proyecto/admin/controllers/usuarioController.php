<?php

class usuarioController extends Controller{

	private $_usuario;
	private $acl;

	public function __construct(){
        parent::__construct();
        $this->_usuario = $this->loadModel('usuario');
        $this->acl = $this->loadModel('acl');
    }

    public function index($tipoBusqueda = false, $busqueda = false, $tipoUsuario = false, $pagina = false){

        $this->_view->setJs(array('usuario'));

        $this->_view->roles = $this->acl->getRoles();

        $filtro = array();

        $param = "";

        $this->_view->busqueda = '';


        if($tipoBusqueda && $busqueda){

            if((strtolower($tipoBusqueda) == 'nombre' || strtolower($tipoBusqueda) == 'apellido') && $busqueda != "" && $busqueda != "ninguno"){


                $filtro['tipoBusqueda'] = strtolower($tipoBusqueda);
                $param = strtolower($tipoBusqueda);
                $this->_view->tipoBusqueda_selected = strtolower($tipoBusqueda);


                $filtro['busqueda'] =  $this->filtrarSql($busqueda);
                $param .= "/" . $this->filtrarSql($busqueda);
                $this->_view->busqueda =  $this->filtrarSql($busqueda);

            }else{
                $param = "tipoBusqueda";
                $param .= "/ninguno";
            }
        }

        if($tipoUsuario){

            if($this->filtrarInt($tipoUsuario)){
                $filtro['tipoUsuario'] = $this->filtrarInt($tipoUsuario);
                $param .= "/". $this->filtrarInt($tipoUsuario);
                $this->_view->tipoUsuario_selected = $this->filtrarInt($tipoUsuario);
            }else{
                $param .= "/Admin";
            }
        }

        if(!$this->filtrarInt($pagina)){
            $pagina = false;
            $this->_view->pagina = 1;
        }else{
            $pagina = (int) $pagina;
            $this->_view->pagina = $pagina;
        }

        $this->getLibrary('paginador');
        $paginador = new Paginador();



        if($this->getInt("enviar") == 1){

            $param = "";

            if($this->getPostParam('busqueda') != ''){
                $filtro['tipoBusqueda'] = $this->getPostParam('tipoBusqueda');
                $filtro['busqueda'] = $this->getSql('busqueda');

                $this->_view->tipoBusqueda_selected = strtolower($this->getPostParam('tipoBusqueda'));
                $this->_view->busqueda = $this->getSql('busqueda');

                $param = strtolower($this->getPostParam('tipoBusqueda'));
                $param .= "/" . $this->getSql('busqueda');

            }else{
                $param = "tipoBusqueda";
                $param .= "/ninguno";
                $this->_view->busqueda = '';
            }

            $filtro['tipoUsuario'] = $this->getPostParam('tipoUsuario');
            $this->_view->tipoUsuario_selected = $this->getPostParam('tipoUsuario');

            $param .= "/" . $this->getPostParam('tipoUsuario');

        }

        $result = $this->_usuario->getUsuariosByFiltro($filtro);

        $this->_view->resultado = $paginador->paginar($result, $param, $pagina);

        $this->_view->paginacion = $paginador->getView('prueba', 'usuario/index');


        $this->_view->filtro = $filtro;
        $this->_view->param = $param;

    	$this->_view->titulo = 'Usuarios';
    	$this->_view->renderizar('index');

    	
    }

    public function insertar(){

        $this->_view->setJs(array('validarNuevo'));

        $this->_view->roles = $this->acl->getRoles();

        $this->_view->paises = $this->acl->getPaises();

        if($this->getInt("enviar") == 1){
            if(isset($_POST['check_rol'])){
                $roles_seleccionados = $_POST['check_rol'];
                $this->_view->roles_seleccionados = $roles_seleccionados;
            }


            $this->_view->datos = $_POST;

            var_dump($_POST);

            $validado = true;

            //si esta vacio el rol

            if(empty($_POST['check_rol'])){
                $this->_view->_error_rol = 'Debe seleccionar por lo menos un rol';
                $validado = false;
            }
            
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error_usuario = 'Debe introducir su nombre de usuario';
                $validado = false;

            }
            
                        
            if(!(strlen($this->getAlphaNum('usuario')) > 3)){
                $this->_view->_error_usuario = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            //^[a-zA-Z]((\.|_|-)?[a-zA-Z0-9]+){3}$
            $exp = '/^[a-zA-Z0-9-_]+$/';
            
            if(!preg_match($exp, $this->getPostParam('usuario'))){
                $this->_view->_error_usuario = 'nombre de usuario inv&aacute;lido';
                $validado = false;

            }

            
            if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error_usuario = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                $validado = false;

            }
            
            
            if(!$this->getSql('pass')){
                $this->_view->_error_pass = 'Debe introducir su password';
                $validado = false;

            }
            
            if(!(strlen($this->getSql('pass')) > 3)){
                $this->_view->_error_pass = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                $validado = false;

            }
            
            if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                $this->_view->_error_pass_confir = 'Los password no coinciden';
                $validado = false;
            }
      
            if(!$this->getSql('primerNombre')){
                $this->_view->_error_primerNombre = 'Debe introducir su nombre';
                $validado = false;

            }
            
            if(!(strlen($this->getSql('primerNombre')) > 2)){
                $this->_view->_error_primerNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                $validado = false;

            }
            
            $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
            
            if(!preg_match($exp, $this->getPostParam('primerNombre'))){
                $this->_view->_error_primerNombre = 'Nombre inv&aacute;lido';
                $validado = false;

            }
                
            $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

            if(!$this->getSql('apellido')){
                $this->_view->_error_apellido = 'Debe introducir su apellido';
                $validado = false;

            }
            
            if(!(strlen($this->getSql('apellido')) > 3)){
                $this->_view->_error_apellido = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                $validado = false;

            }
            
            
            if(!preg_match($exp, $this->getPostParam('apellido'))){
                $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
                $validado = false;
            }

            if(!$this->getPostParam('email')){
                $this->_view->_error_email = 'Debe introducir su cuenta de correo electr&oacute;nico';
                $validado = false;
            }
            
            if(!$this->validarEmail($this->getPostParam('email'))){
                $this->_view->_error_email = 'La direcci&oacute;n de email es inv&aacute;lida';
                $validado = false;
            }
            
            if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                $this->_view->_error_email = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                $validado = false;
            }

            $exp = '/^\\d{11,14}$/';
            
            if(!preg_match($exp, $this->getPostParam('telefono'))){
                $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                $validado = false;
            }
            
            if($this->getInt('pais') == 0){
                $this->_view->_error_pais = 'Debe seleccionar un pa&iacute;s';
                $validado = false;
            }
            if(!$this->getSql('din')){
                $this->_view->_error_din = 'Debe introducir su DIN';
                $validado = false;
            }

            if($this->_usuario->verificarDin($this->getSql('din'))){
                $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                $validado = false;
            }

            if($validado){
                var_dump("es valido...! :)");
                $this->_usuario->setUsuario($_POST);
            }



        }

        $this->_view->titulo = 'Insertar Usuario';
        $this->_view->renderizar('form');
    }

}

?>