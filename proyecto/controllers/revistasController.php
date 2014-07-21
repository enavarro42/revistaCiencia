<?php

class revistasController extends Controller{
    
    private $_revistas;
    
    public function __construct(){
        parent::__construct();
        $this->_revistas = $this->loadModel('revistas');
    }
    
    public function index(){
        

        $this->_view->revistas = $this->_revistas->getRevistas();
        $this->_view->titulo = 'Revistas';
        $this->_view->renderizar('index', 'revistas');

    }
    
    public function info($revista){
        if($revista){
            
            $revista = strip_tags($revista);
            
             if(!get_magic_quotes_gpc()){
                 $revista = pg_escape_string($revista);
             }
            
            $this->_view->revista = $this->_revistas->getRevista(trim($revista));
            $this->_view->titulo = "Revista - ". $this->_view->revista['nombre'];
            $this->_view->renderizar('info', 'revistas');
        }else{
            header('location:' . BASE_URL . 'error/access/404');
            exit;
        }
    }
}

?>