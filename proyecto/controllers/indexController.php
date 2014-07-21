<?php
class indexController extends Controller{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $post = $this->loadModel('post');
        
        $this->_view->titulo = 'Revistas Arbitradas';
        $this->_view->renderizar('index', 'inicio');
    }
}
?>