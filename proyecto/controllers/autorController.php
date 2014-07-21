<?php

class autorController extends Controller{
    public function __construct() {
        parent::__construct();
        $this->_autor = $this->loadModel('autor');
    }
    
    public function index(){
        
        
        if(!Session::get('autenticado')){
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }else{

            //temporalmenta hasta pensarlo bien...!
            Session::set('level', 'autor');

            $this->_view->titulo = 'Autor';
            $this->redireccionar('manuscrito');
        }
        
        
        
    }
    
    public function getPersona(){
        $arreglo = array();
        $arreglo['id'] = 0;
        if(isset($_SESSION['id_persona']))
            $arreglo['id'] = (int)$_SESSION['id_persona'];
        
        echo json_encode($arreglo);
    }
    
    public function getDatosAutor(){
        $id_persona = 0;
        $resp = NULL;
        if(isset($_SESSION['id_persona'])){
            $id_persona = (int)$_SESSION['id_persona'];
            $resp = $this->_autor->getDatos($id_persona);
        }
        
        echo json_encode($resp);
        
    }
    
}
?>