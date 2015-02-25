<?php

class reporteController extends Controller{
    
    private $_reporte;

    public function __construct(){
        parent::__construct();
        $this->_reporte = $this->loadModel('reporte');
    }
    
    public function index(){

        $this->getLibrary('mpdf_master/mpdf');

        $stylesheet = file_get_contents(ROOT . 'views/layout/default/css/bootstrap.min.css');

        $this->_view->stylesheet = $stylesheet;

        $this->_view->titulo = 'Reportes';
        $this->_view->renderizar('index', 'reporte');
    }
}

?>