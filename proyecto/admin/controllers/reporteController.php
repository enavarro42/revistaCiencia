<?php

class reporteController extends Controller{
    
    private $_reporte;
    private $_usuario;
    private $acl;


    public function __construct(){
        parent::__construct();
        $this->_reporte = $this->loadModel('reporte');
        $this->_usuario = $this->loadModel('usuario');
        $this->acl = $this->loadModel('acl');
    }
    
    public function index(){

        $this->_view->titulo = 'Reportes';

         $this->_view->url_img = $this->getUrlImage();

        $this->_view->urlReporteUsuario = $this->getUrl("reporte/preparar_reporteUsuario");
        $this->_view->urlReporteTotalUsuario = $this->getUrl("reporte/preparar_reporteGraficos");

        $this->_view->urlReporteManuscrito = $this->getUrl("reporte/preparar_reporteManuscrito");

        $this->_view->renderizar('index', 'reporte');
    }

    public function preparar_reporteUsuario(){

        $this->getLibrary('html2pdf/html2pdf.class');

        $this->_view->url_img = $this->getUrlImage();

        $this->_view->roles = $this->acl->getRoles();
        $this->_view->areas = $this->_usuario->getAllAreas();

        if($this->getInt("enviar")){

            $filtro = array();

            $rol = $this->getInt("rol");
            $area = $this->getInt("area");
            if($rol){
                $filtro["rol"] = $this->getInt("rol");
            }

            if($area){
                $filtro["area"] = $this->getInt("area");
            }

            $reporte = $this->_reporte->getUsuarioByFiltro($filtro);
            $this->_view->reporte = $reporte;
            $this->_view->renderizar('generar_reporteUsuario', 'reporte');
        }else{

            $this->_view->titulo = 'Reportes de Usuarios';
            $this->_view->renderizar('preparar_reporteUsuario', 'reporte');

        }
    }


    public function preparar_reporteGraficos(){

        $this->getLibrary('html2pdf/html2pdf.class');

        $this->getLibrary('libchart/classes/libchart');

        $this->_view->url_img = $this->getUrlImage();


        $result1 = $this->_reporte->getUsuarioPorArea();

        if($result1){

            $this->_view->result1 = $result1;
        }

        $result2 = $this->_reporte->getRolPorPersona();

        if($result2){

            $this->_view->result2 = $result2;
        }

        $result3 = $this->_reporte->getManuscritoPorEstatus();


        if($result3){

            $this->_view->result3 = $result3;
        }
        $this->_view->titulo = 'Reportes de Usuarios';
        $this->_view->renderizar('generar_reporteTotalUsuario', 'reporte');
       
    }



    public function preparar_reporteManuscrito(){

        $this->getLibrary('html2pdf/html2pdf.class');
        $this->_view->url_img = $this->getUrlImage();

        $result = $this->_reporte->getManuscritosParaAdmin();

        if($result){

            $this->_view->result = $result;
        }
        $this->_view->titulo = 'Reportes de Manuscritos';
        $this->_view->renderizar('generar_reporteManuscrito', 'reporte');

    }



}

?>