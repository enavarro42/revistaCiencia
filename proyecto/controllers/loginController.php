<?php

class loginController extends Controller{
    
    private $_login;
    
    public function __construct(){
        parent::__construct();
        $this->_login = $this->loadModel('login');
    }
    
    public function index(){
        $this->_view->titulo = 'Iniciar Sesi&oacute;n';
        
        if(!Session::get('autenticado')){
        
            if($this->getInt('enviar') == 1){
                $this->_view->datos = $_POST;

                if(!$this->getAlphaNum('usuario')){
                    $this->_view->_error = 'Debe introducir su nombre de usuario';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }

                if(!$this->getSql('pass')){
                    $this->_view->_error = 'Debe introducir su password';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }


                $row = $this->_login->getUsuario(
                        $this->getAlphaNum('usuario'),
                        $this->getSql('pass')
                        );

                if(!$row){
                    $this->_view->_error = 'Usuario y/o password incorrectos';
                    $this->_view->renderizar('index', 'login');
                    exit;
                }

                if($row['estado'] != 1){
                    $this->_view->_error = 'Este usuario no esta habilitado';
                }

                Session::set('autenticado', true);
                //Session::set('level', $row['rol']);

                $row_persona_rol = $this->_login->getPersonaRol($row['id_persona']);

                $levels = array();
                for($i = 0; $i < count($row_persona_rol); $i++)
                    $levels[] = $this->_login->getRol($row_persona_rol[$i][1]);

                Session::set('levels', $levels);
                //temporalmente esto es de prueba...!
               // Session::set('level', 'admin');

                Session::set('usuario', $row['usuario']);
                Session::set('id_usuario', $row['id_usuario']);
                Session::set('id_persona', $row['id_persona']);
                Session::set('tiempo', time());


                $this->redireccionar('usuario');
            }

            $this->_view->renderizar('index', 'login');
        }else{
            $this->redireccionar();
        }
    }
    
    public function usuario_logeado(){
        if(isset($_SESSION['usuario']))
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