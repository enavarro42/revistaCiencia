<?php
class accesoController extends Controller{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        
        $this->_view->titulo = 'Revistas Arbitradas';
        $this->_view->renderizar('index', 'acceso');
    }
}
?>