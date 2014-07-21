<?php

class oficinaController extends Controller{
    
    private $_oficina;
    private $_revista;
    
    public function __construct(){
        parent::__construct();
        $this->_oficina = $this->loadModel('oficina');
        $this->_revista = $this->loadModel('revistas');
    }
    
    public function index(){
        
        $this->_view->datosOficina = $this->_oficina->getDatosOficina();
        $this->_view->revistas = $this->_revista->getRevistas();
        $this->_view->titulo = 'Oficina de Publicaciones';
        $this->_view->renderizar('index', 'oficina');
    }
}

?>