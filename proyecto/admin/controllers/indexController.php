<?php
class indexController extends Controller{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        // $post = $this->loadModel('post');

        if(!Session::get('autenticado_admin')){
        	$this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        $this->_view->setJs(array('controlCarousel'));
        
        $this->_view->titulo = 'Revistas Arbitradas';
        $this->_view->renderizar('index', 'inicio');
    }
}
?>