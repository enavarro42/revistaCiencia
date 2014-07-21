<?php

class usuarioController extends Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        if(!Session::get('autenticado')){
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }else{
            $this->_view->titulo = 'Usuario';
            $this->_view->renderizar('index', 'usuario');
        }
    }
    
}

?>