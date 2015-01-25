<?php

class autorController extends Controller{
    public function __construct() {
        parent::__construct();
        $this->_persona = $this->loadModel('persona');
    }
    
    public function index(){
        
        
        if(!Session::get('autenticado')){
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }else{

            //temporalmenta hasta pensarlo bien...!
            Session::set('level', 'Autor');

            $this->_view->titulo = 'Autor';
            $this->redireccionar('manuscrito');
        }
        
        
        
    }
    
    public function getPersona(){
        $arreglo = array();
        $arreglo['id'] = 0;
        if(isset($_SESSION['id_person']))
            $arreglo['id'] = (int)$_SESSION['id_person'];
        
        echo json_encode($arreglo);
    }
    
    public function getDatosAutor(){
        $id_persona = 0;
        $resp = NULL;
        if(isset($_SESSION['id_person'])){
            $id_persona = (int)$_SESSION['id_person'];
            $resp = $this->_persona->getDatos($id_persona);
        }
        
        echo json_encode($resp);
        
    }
    
}
?>