<?php

class ajaxController extends Controller{
    
    private $_ajax;
    
    public function __construct(){
        parent::__construct();
        $this->_ajax = $this->loadModel('ajax');
    }
    
    public function index(){
        $this->_view->titulo = 'Ajax';
        $this->_view->setJs(array('ajax'));
        $this->_view->paises = $this->_ajax->getPaises();
        $this->_view->renderizar('index', 'ajax');
    }
    
    public function getCiudades(){
        if($this->getInt('pais'))
            echo json_encode($this->_ajax->getCiudades($this->getInt('pais')));
    }
    
    public function getPaises(){
        echo json_encode($this->_ajax->getPaises());
    }
    
    public function getRevistas(){
        echo json_encode($this->_ajax->getRevistas());
    }
    
    public function getAreas(){
        echo json_encode($this->_ajax->getAreas());
    }
    
    
        public function getIdiomas(){
        echo json_encode($this->_ajax->getIdiomas());
    }
    
    public function insertarCiudad(){
        if($this->getInt('pais') && $this->getSql('ciudad')){
            $this->_ajax->insertarCiudad(
                    $this->getSql('ciudad'),
                    $this->getInt('pais')
                    );
        }
    }
}

?>