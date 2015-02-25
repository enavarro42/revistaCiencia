<?php

class usuarioController extends Controller{

	private $_usuario;
	private $acl;

	public function __construct(){
        parent::__construct();
        $this->_usuario = $this->loadModel('usuario');
        $this->acl = $this->loadModel('acl');
    }

    public function index($tipoBusqueda = false, $busqueda = false, $tipoUsuario = false, $area = false, $pagina = false){

        $view_key = "ver_usuario";

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

        $view_key = "eliminar_usuario";

        if(array_key_exists($view_key, $this->_view->acl) == false){
            $eliminar = 0;
        }

        $this->_view->eliminar = $eliminar;
        

        $this->_view->setJs(array('usuario'));

        $this->_view->roles = $this->acl->getRoles();
        $this->_view->areas = $this->_usuario->getAllAreas();


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
                $param .= "/Todos";
            }
        }

        if($area){

            if($this->filtrarInt($area)){
                $filtro['area'] = $this->filtrarInt($area);
                $param .= "/". $this->filtrarInt($area);
                $this->_view->area_selected = $this->filtrarInt($area);
            }else{
                $param .= "/Todos";
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

            if($this->getPostParam('tipoUsuario') != 0){

                $filtro['tipoUsuario'] = $this->getPostParam('tipoUsuario');
            }

            if($this->getPostParam('area') != 0){
                 $filtro['area'] = $this->getPostParam('area');
            }

            $this->_view->tipoUsuario_selected = $this->getPostParam('tipoUsuario');

            $this->_view->area_selected = $this->getPostParam('area');

            $param .= "/" . $this->getPostParam('tipoUsuario');

            $param .= "/" . $this->getPostParam('area');

        }

        $result = $this->_usuario->getUsuariosByFiltro($filtro);

        // var_dump($result);

        // var_dump($filtro);

        $this->_view->resultado = $paginador->paginar($result, $param, $pagina);

        $this->_view->paginacion = $paginador->getView('prueba', 'usuario/index');


        $this->_view->filtro = $filtro;
        $this->_view->param = $param;

    	$this->_view->titulo = 'Usuarios';
    	$this->_view->renderizar('index');

    	
    }

    public function insertar(){

        $view_key = "insertar_usuario";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();


        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $this->_view->tipoAccion = 'insertar';

        $this->_view->cajaPass = 'block';
        $this->_view->linkCambiarPass = 'none';

        $this->_view->setJs(array('validarForm'));

        $this->_view->roles = $this->acl->getRoles();

        $this->_view->paises = $this->acl->getPaises();

        $this->_view->areas = $this->_usuario->getAllAreas();

        if($this->getInt("enviar") == 1){

            if(isset($_POST['check_rol'])){
                $roles_seleccionados = $_POST['check_rol'];
                $this->_view->roles_seleccionados = $roles_seleccionados;
            }

            if(isset($_POST['check_areas'])){
                $areas_seleccionados = $_POST['check_areas'];
                $this->_view->areas_seleccionados = $areas_seleccionados;
            }


            $this->_view->datos = $_POST;

            //var_dump($_POST);

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

            // contraseña aleatoria
            $arreglo = $_POST;

            if($_POST['optionsRadios'] == 'option1'){
                $psswd = substr( md5(microtime()), 1, 8);

                $arreglo['pass'] = $psswd;
                $arreglo['confirmar'] = $psswd;
            }


            

            // asignar contraseña

            if($_POST['optionsRadios'] == 'option2'){
            
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

            //--------------------------Segundo Nombre----------------------------
            if($this->getSql('segundoNombre') != ''){
                if(!(strlen($this->getSql('segundoNombre')) > 2)){
                    $this->_view->_error_segundoNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $validado = false;

                }
                
                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                
                if(!preg_match($exp, $this->getPostParam('segundoNombre'))){
                    $this->_view->_error_segundoNombre = 'Segundo nombre inv&aacute;lido';
                    $validado = false;

                }
            }

                    //Apellido 
        
        if(!$this->getSql('apellido')){
            $this->_view->_error_apellido = 'Debe introducir su apellido';
            $validado = false;
        }

        if(!(strlen($this->getSql('apellido')) > 2)){
            $this->_view->_error_apellido = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
            $validado = false;
        }

        $test_apellido1 = true;
        $test_apellido2 = true;

        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

        if(!preg_match($exp, $this->getPostParam('apellido'))){
            $test_apellido1 = false;
        }


        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
        
        
        if(!preg_match($exp, $this->getPostParam('apellido'))){
            // $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
            //$validado = false;
            $test_apellido2 = false;
        }

        if($test_apellido1 == false && $test_apellido2 == false){
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

            if($this->getPostParam('telefono') != ''){
            
                if(!preg_match($exp, $this->getPostParam('telefono'))){
                    $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                    $validado = false;
                }
            }
            
            if($this->getInt('pais') == 0){
                $this->_view->_error_pais = 'Debe seleccionar un pa&iacute;s';
                $validado = false;
            }

            if(empty($_POST['check_areas'])){
                $this->_view->_error_area = 'Debe seleccionar por lo menos un &aacute;rea';
                $validado = false;
            }

            // if(!$this->getSql('din')){
            //     $this->_view->_error_din = 'Debe introducir su DIN';
            //     $validado = false;
            // }
            if($this->getSql('din') != ''){
                if($this->_usuario->verificarDin($this->getSql('din'))){
                    $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                    $validado = false;
                }
            }

            if(!isset($_POST['genero'])){
                $this->_view->_error_genero = 'Debe seleccionar un g&eacute;nero.';
                $validado = false;
            }


             // var_dump($arreglo);

            if($validado){
                //var_dump("es valido...! :)");
                $this->_usuario->setUsuario($arreglo);

                $this->redireccionar('usuario');
            }

        }
        

        $this->_view->titulo = 'Insertar Usuario';
        $this->_view->renderizar('form');
    }

    function generar_clave($longitud){ 
       $cadena="[^A-Z0-9]"; 
       return substr(eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())), 
       0, $longitud); 
    }  

    public function editar($id_persona = false){

        $view_key = "editar_usuario";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();


        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $this->_view->tipoAccion = 'editar';

        $this->_view->cajaPass = 'none';
        $this->_view->linkCambiarPass = 'block';

        $persona = $this->_usuario->getPersona($this->filtrarInt($id_persona));
        $usuario = $this->_usuario->getUsuario($this->filtrarInt($id_persona));

        //si se envio algo por para metro, entonces proceder
        if($id_persona && $persona){

            $this->_view->id_persona = $this->filtrarInt($id_persona);

            $this->_view->setJs(array('validarForm'));

            $this->_view->roles = $this->acl->getRoles();

            $this->_view->paises = $this->acl->getPaises();

            $this->_view->areas = $this->_usuario->getAllAreas();

            if($this->getInt("enviar") == 1){


                if(isset($_POST['check_rol'])){
                    $roles_seleccionados = $_POST['check_rol'];
                    $this->_view->roles_seleccionados = $roles_seleccionados;
                }

                if(isset($_POST['check_areas'])){
                    $areas_seleccionados = $_POST['check_areas'];
                    $this->_view->areas_seleccionados = $areas_seleccionados;
                }


                $this->_view->datos = $_POST;

                //var_dump($_POST);

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

                if(trim($this->getAlphaNum('usuario')) != trim($usuario['usuario'])){

                
                    if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                        $this->_view->_error_usuario = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                        $validado = false;

                    }
                }

                if($this->getInt('nuevaPass')){

                    $this->_view->nuevaPass = $this->getInt('nuevaPass');

                    $this->_view->cajaPass = 'block';
                
                    if(!$this->getSql('pass')){
                        $this->_view->_error_pass = 'Debe introducir su contrase&ntilde;a';
                        $validado = false;

                    }
                    
                    if(!(strlen($this->getSql('pass')) > 3)){
                        $this->_view->_error_pass = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                        $validado = false;

                    }
                    
                    if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                        $this->_view->_error_pass_confir = 'Las contrase&ntilde;as no coinciden';
                        $validado = false;
                    }
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


                if($this->getSql('segundoNombre') != ''){
                    if(!(strlen($this->getSql('segundoNombre')) > 2)){
                        $this->_view->_error_segundoNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                        $validado = false;

                    }
                    
                    $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                    
                    if(!preg_match($exp, $this->getPostParam('segundoNombre'))){
                        $this->_view->_error_segundoNombre = 'Segundo nombre inv&aacute;lido';
                        $validado = false;

                    }
                }

        if(!$this->getSql('apellido')){
            $this->_view->_error_apellido = 'Debe introducir su apellido';
            $validado = false;
        }

        if(!(strlen($this->getSql('apellido')) > 2)){
            $this->_view->_error_apellido  = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
            $validado = false;
        }

        $test_apellido1 = true;
        $test_apellido2 = true;

        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

        if(!preg_match($exp, $this->getPostParam('apellido'))){
            $test_apellido1 = false;
        }


        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
        
        
        if(!preg_match($exp, $this->getPostParam('apellido'))){
            // $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
            //$validado = false;
            $test_apellido2 = false;
        }

        if($test_apellido1 == false && $test_apellido2 == false){
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


                if(trim($this->getPostParam('email')) != trim($persona['email'])){
                
                    if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                        $this->_view->_error_email = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                        $validado = false;
                    }
                }


                // $exp = '/^\\d{11,14}$/';
                
                // if(!preg_match($exp, $this->getPostParam('telefono'))){
                //     $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                //     $validado = false;
                // }

                $exp = '/^\\d{11,14}$/';

                if($this->getPostParam('telefono') != ''){
                
                    if(!preg_match($exp, $this->getPostParam('telefono'))){
                        $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                        $validado = false;
                    }
                }
                
                if($this->getInt('pais') == 0){
                    $this->_view->_error_pais = 'Debe seleccionar un pa&iacute;s';
                    $validado = false;
                }
                // if(!$this->getSql('din')){
                //     $this->_view->_error_din = 'Debe introducir su DIN';
                //     $validado = false;
                // }


                if(trim($this->getAlphaNum('din')) != trim($persona['din'])){

                    if($this->_usuario->verificarDin($this->getSql('din'))){
                        $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                        $validado = false;
                    }
                }

                if(empty($_POST['check_areas'])){
                    $this->_view->_error_area = 'Debe seleccionar por lo menos un &aacute;rea';
                    $validado = false;
                }



                if($validado){

                    $this->_usuario->editarUsuario($id_persona, $_POST);
                    $this->_view->correo = $_POST;
                    $this->redireccionar('usuario');

                }

                $this->_view->titulo = 'Editar Usuario';
                $this->_view->renderizar('form');

            }else{

                // $persona = $this->_usuario->getPersona($this->filtrarInt($id_persona));
                $persona_rol = $this->_usuario->getPersonaRol($this->filtrarInt($id_persona));
                // $usuario = $this->_usuario->getUsuario($this->filtrarInt($id_persona));

                if($persona && $persona_rol && $usuario){

                    $datos['primerNombre'] = trim($persona['primerNombre']);
                    $datos['apellido'] = trim($persona['apellido']);
                    $datos['genero'] = $persona['genero'];
                    $datos['email'] = trim($persona['email']);
                    $datos['telefono'] = trim($persona['telefono']);
                    $datos['pais'] = $persona['pais'];
                    $datos['resumenBiografico'] = trim($persona['resumenBiografico']);
                    $datos['din'] = trim($persona['din']);
                    $datos['filiacion'] = trim($persona['filiacion']);
                    $datos['segundoNombre'] = trim($persona['segundoNombre']);

                    $datos['usuario'] = trim($usuario['usuario']);
                    $datos['pass'] = trim($usuario['pass']);
                    $datos['confirmar'] = trim($usuario['pass']);


                    $areas = array();

                    $areas_seleccionados = $this->_usuario->getAreaByPersona($this->filtrarInt($id_persona));

                    for($i = 0; $i < count($areas_seleccionados); $i++){
                        $areas[] = $areas_seleccionados[$i]['id_area'];
                    }

                    $this->_view->areas_seleccionados = $areas;

                    $roles_seleccionados = array();

                    for($i=0; $i<count($persona_rol); $i++){
                        $datos['check_rol'][] = $persona_rol[$i]['id_rol'];

                        $roles_seleccionados[] = $persona_rol[$i]['id_rol'];
                        
                    }

                    $this->_view->roles_seleccionados = $roles_seleccionados;

                    $this->_view->datos = $datos;



                }else{
                    $this->redireccionar('usuario');
                }

                $this->_view->titulo = 'Editar Usuario';
                $this->_view->renderizar('form');
            }

        }else{
            $this->redireccionar('usuario');
        }
    }

    public function eliminar(){
        $this->_usuario->eliminar($_POST["ids"]);
    }


    public function comprobarUsuario(){
        $resp['resp'] = "<span style='color:green;'>Disponible...!</span>";

        $exp = '/^[a-zA-Z0-9-_]+$/';

        if(isset($_POST['id_persona'])){

            $id_persona = $_POST['id_persona'];

            $usuario = $this->_usuario->getUsuario($id_persona);
        }

  
        if(!preg_match($exp, $_POST['usuario'])){
            $resp['resp'] = 'nombre de usuario inv&aacute;lido';
        }

        if(!(strlen($this->getAlphaNum('usuario')) > 3)){
            $resp['resp'] = 'Introduzca como m&iacute;nimo 4 car&aacute;cteres';
        }
        
        if($_POST['usuario'] == "") $resp['resp'] = "Debe llenar el nombre de usuario";


        if(isset($usuario) && $usuario){
            if(trim($usuario['usuario']) != trim($_POST['usuario'])){

                if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                    $resp['resp'] = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                }

            }else{
                $resp['resp'] = "";
            }
        }else{
                if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                    $resp['resp'] = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                }
        }


        echo $resp['resp'];
    }
    
    public function comprobarEmail(){
        
        
        $resp['resp'] = "";

        if(isset($_POST['id_persona'])){

            $id_persona = $_POST['id_persona'];

            $persona = $this->_usuario->getPersona($id_persona);
        }

        if(!$this->validarEmail($this->getPostParam('email'))){
            $resp['resp'] = 'La direcci&oacute;n de correo es inv&aacute;lida';
        }

        if($_POST['email'] == "") $resp['resp'] = "Debe llenar el campo correo";

        if(isset($persona) && $persona){
            if(trim($persona['email']) != trim($_POST['email'])){

                if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                    $resp['resp'] = 'Esta direcci&oacute;n de correo ya est&aacute; registrada';
                }

            }else{
                $resp['resp'] = "";
            }
        }else{
            if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                $resp['resp'] = 'Esta direcci&oacute;n de correo ya est&aacute; registrada';
            }
        } 
    
        echo $resp['resp'];
    }

    public function perfil(){

        $id_persona = $_SESSION["id_persona"];
        $this->_view->tipoAccion = 'editar';

        $this->_view->cajaPass = 'none';
        $this->_view->linkCambiarPass = 'block';

        $persona = $this->_usuario->getPersona($this->filtrarInt($id_persona));
        $usuario = $this->_usuario->getUsuario($this->filtrarInt($id_persona));

        //si se envio algo por para metro, entonces proceder
        if($id_persona && $persona){

            $this->_view->id_persona = $this->filtrarInt($id_persona);

            $this->_view->setJs(array('validarForm'));

            // $this->_view->roles = $this->acl->getRoles();

            $this->_view->paises = $this->acl->getPaises();

            $this->_view->areas = $this->_usuario->getAllAreas();

            if($this->getInt("enviar") == 1){


                // if(isset($_POST['check_rol'])){
                //     $roles_seleccionados = $_POST['check_rol'];
                //     $this->_view->roles_seleccionados = $roles_seleccionados;
                // }

                if(isset($_POST['check_areas'])){
                    $areas_seleccionados = $_POST['check_areas'];
                    $this->_view->areas_seleccionados = $areas_seleccionados;
                }


                $this->_view->datos = $_POST;

                //var_dump($_POST);

                $validado = true;

                //si esta vacio el rol

                // if(empty($_POST['check_rol'])){
                //     $this->_view->_error_rol = 'Debe seleccionar por lo menos un rol';
                //     $validado = false;
                // }
                
                
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

                if(trim($this->getAlphaNum('usuario')) != trim($usuario['usuario'])){

                
                    if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                        $this->_view->_error_usuario = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                        $validado = false;

                    }
                }

                if($this->getInt('nuevaPass')){

                    $this->_view->nuevaPass = $this->getInt('nuevaPass');

                    $this->_view->cajaPass = 'block';
                
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


                if($this->getSql('segundoNombre') != ''){
                    if(!(strlen($this->getSql('segundoNombre')) > 2)){
                        $this->_view->_error_segundoNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                        $validado = false;

                    }
                    
                    $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                    
                    if(!preg_match($exp, $this->getPostParam('segundoNombre'))){
                        $this->_view->_error_segundoNombre = 'Segundo nombre inv&aacute;lido';
                        $validado = false;

                    }
                }

                //apellido solo

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!$this->getSql('apellido')){
                    $this->_view->_error_apellido = 'Debe introducir su apellido';
                    $validado = false;

                }
                
                if(!(strlen($this->getSql('apellido')) > 3)){
                    $this->_view->_error_apellido = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                    $validado = false;

                }

                $pivote = 0;
                
                
                if(!preg_match($exp, $this->getPostParam('apellido'))){
                    $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
                    // $validado = false;
                    $pivote = 1;
                }

                // dos apellidos
                    
                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                
                
                if(!preg_match($exp, $this->getPostParam('apellido'))){
                    $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
                    // $validado = false;
                    $pivote = 1;
                }else{
                    $pivote = 0;
                }

                if($pivote){
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


                if(trim($this->getPostParam('email')) != trim($persona['email'])){
                
                    if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                        $this->_view->_error_email = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                        $validado = false;
                    }
                }


                // $exp = '/^\\d{11,14}$/';
                
                // if(!preg_match($exp, $this->getPostParam('telefono'))){
                //     $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                //     $validado = false;
                // }

                $exp = '/^\\d{11,14}$/';

                if($this->getPostParam('telefono') != ''){
                
                    if(!preg_match($exp, $this->getPostParam('telefono'))){
                        $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                        $validado = false;
                    }
                }
                
                if($this->getInt('pais') == 0){
                    $this->_view->_error_pais = 'Debe seleccionar un pa&iacute;s';
                    $validado = false;
                }
                // if(!$this->getSql('din')){
                //     $this->_view->_error_din = 'Debe introducir su DIN';
                //     $validado = false;
                // }


                if(trim($this->getAlphaNum('din')) != trim($persona['din'])){

                    if($this->_usuario->verificarDin($this->getSql('din'))){
                        $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                        $validado = false;
                    }
                }

                if(empty($_POST['check_areas'])){
                    $this->_view->_error_area = 'Debe seleccionar por lo menos un &aacute;rea';
                    $validado = false;
                }



                if($validado){

                    var_dump($_POST);

                    $this->_usuario->editarPerfil($id_persona, $_POST);
                    $this->_view->correo = $_POST;
                    $this->redireccionar();

                }

                $this->_view->titulo = 'Editar Usuario';
                $this->_view->renderizar('form');

            }else{

                // $persona = $this->_usuario->getPersona($this->filtrarInt($id_persona));
                $persona_rol = $this->_usuario->getPersonaRol($this->filtrarInt($id_persona));
                // $usuario = $this->_usuario->getUsuario($this->filtrarInt($id_persona));

                if($persona && $persona_rol && $usuario){

                    $datos['primerNombre'] = trim($persona['primerNombre']);
                    $datos['apellido'] = trim($persona['apellido']);
                    $datos['genero'] = $persona['genero'];
                    $datos['email'] = trim($persona['email']);
                    $datos['telefono'] = trim($persona['telefono']);
                    $datos['pais'] = $persona['pais'];
                    $datos['resumenBiografico'] = trim($persona['resumenBiografico']);
                    $datos['din'] = trim($persona['din']);
                    $datos['filiacion'] = trim($persona['filiacion']);
                    $datos['segundoNombre'] = trim($persona['segundoNombre']);

                    $datos['usuario'] = trim($usuario['usuario']);
                    $datos['pass'] = trim($usuario['pass']);
                    $datos['confirmar'] = trim($usuario['pass']);


                    $areas = array();

                    $areas_seleccionados = $this->_usuario->getAreaByPersona($this->filtrarInt($id_persona));

                    for($i = 0; $i < count($areas_seleccionados); $i++){
                        $areas[] = $areas_seleccionados[$i]['id_area'];
                    }

                    $this->_view->areas_seleccionados = $areas;

                    // $roles_seleccionados = array();

                    // for($i=0; $i<count($persona_rol); $i++){
                    //     $datos['check_rol'][] = $persona_rol[$i]['id_rol'];

                    //     $roles_seleccionados[] = $persona_rol[$i]['id_rol'];
                        
                    // }

                    // $this->_view->roles_seleccionados = $roles_seleccionados;

                    $this->_view->datos = $datos;



                }else{
                    $this->redireccionar();
                }

                $this->_view->titulo = 'Perfil';
                $this->_view->renderizar('perfil');
            }

        }else{
            $this->redireccionar();
        }
    }
    

}

?>