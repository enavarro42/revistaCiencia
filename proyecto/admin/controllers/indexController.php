<?php
class indexController extends Controller{

    private $_reporte;
    
    public function __construct(){
        parent::__construct();

        $this->_reporte = $this->loadModel("reporte");
    }
    
    public function index(){
        // $post = $this->loadModel('post');

        $view_key = "index";

        if(!Session::get('autenticado_admin')){
        	$this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $result = $this->_reporte->getUsuarioPorArea();

        if($result){
            $usuario_area = "";

            for($i = 0; $i<count($result); $i++){
                if($i > 0) $usuario_area .= ",";
                $usuario_area .= "['" . trim($result[$i]["nombre"]) . "', ". $result[$i]["cantidad"]. "]";
            }

            $this->_view->usuario_area = $usuario_area;
            $this->_view->titulo_g1 = "Cantidad de Usuarios por Área";
        }

        //Grafica 2

        $result = $this->_reporte->getRolPorPersona();

        if($result){
            $persona_rol = "";

            for($i = 0; $i<count($result); $i++){
                if($i > 0) $persona_rol .= ",";
                $persona_rol .= "['" . trim($result[$i]["rol"]) . "', ". $result[$i]["cantidad"]. "]";
            }

            $this->_view->persona_rol = $persona_rol;
            $this->_view->titulo_g2 = "Cantidad de Personas por Roles";
        }

        //grafica 3

        $result = $this->_reporte->getManuscritoPorEstatus();

        if($result){

            $manuscrito_estatus = "['Estatus', 'Manuscritos', { role: 'style' }],";

            for($i = 0; $i<count($result); $i++){
                if($i > 0) $manuscrito_estatus .= ",";
                $manuscrito_estatus .= "['" . trim($result[$i]["estatus"]) . "', ". $result[$i]["cantidad"]. ", '#3366CC']";
            }
            $this->_view->manuscrito_estatus = $manuscrito_estatus;
            $this->_view->titulo_g3 = "Cantidad de Manuscrito por Estatus";
        }




        $this->_view->setJs(array('controlCarousel'));
        
        $this->_view->titulo = 'Revistas Arbitradas';
        $this->_view->renderizar('index', 'inicio');
    }

}
?>