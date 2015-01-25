<?php

class loginController extends Controller{
    
    private $_login;
    
    public function __construct(){
        parent::__construct();
        $this->_login = $this->loadModel('login');
    }
    
    public function index(){
        $this->_view->titulo = 'Iniciar Sesi&oacute;n';
        $this->_view->_error = "";
        
        if(!Session::get('autenticado_admin')){
        
            if($this->getInt('enviar') == 1){
                $this->_view->datos = $_POST;

                if(!$this->getAlphaNum('usuario')){
                    $this->_view->_error = 'Debe introducir su nombre de usuario';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }

                if(!$this->getSql('pass')){
                    $this->_view->_error = 'Debe introducir su contrase&ntilde;a';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }


                $row = $this->_login->getUsuario(
                        $this->getAlphaNum('usuario'),
                        $this->getSql('pass')
                        );

                //var_dump($row);

                if(empty($row)){
                    $this->_view->_error = 'Usuario y/o Contrase&ntilde;a incorrectos';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }

                $row_persona_rol = $this->_login->getPersonaRol($row['id_persona']);

                $levels = array();
                for($i = 0; $i < count($row_persona_rol); $i++)
                    $levels[] = trim($this->_login->getRol($row_persona_rol[$i][1]));

                //var_dump($levels);

                $valido = false;

                if(in_array('Admin', $levels)){
                    $valido = true;
                    Session::set('level', 'Admin');
                    //var_dump("entro1");
                }
                else if(in_array('Editor', $levels)){
                    $valido = true;
                    Session::set('level', 'Editor');
                    //var_dump("entro2");
                }
                else if(in_array('Secretaria', $levels)){
                    $valido = true;
                    Session::set('level', 'Secretaria');
                    //var_dump("entro2");
                }

               // var_dump($valido);

                if(!$valido){
                    $this->_view->_error = 'Usuario y/o Contrase&ntilde;a incorrectos';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }

                if($row['estado'] != 1){
                    $this->_view->_error = 'Este usuario no est&aacute; habilitado';
                }

                Session::set('autenticado_admin', true);
                //Session::set('level', $row['rol']);

                
                Session::set('levels', $levels);
                //temporalmente esto es de prueba...!
               // Session::set('level', 'admin');

                Session::set('user', $row['usuario']);
                Session::set('id_user', $row['id_usuario']);
                Session::set('id_persona', $row['id_persona']);
                Session::set('tiempo', time());


                $this->redireccionar();
            }

            $this->_view->renderizar('index', 'login');
        }else{
            $this->redireccionar();
        }
    }
    
    public function usuario_logeado(){
        if(isset($_SESSION['user']))
            echo "1";
        else
            echo "0";
    }

    
    public function cerrar(){
        Session::destroy();
        $this->redireccionar();
    }
}

?>